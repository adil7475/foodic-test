<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateRequest;
use App\Jobs\IngredientStockNotificationEmailJob;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    /**
     * @param CreateRequest $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function create(CreateRequest $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity;

        //First check either we have enough quantity to complete the order
        $product = Product::findOrFail($productId);
        $product->load('ingredients');

        foreach ($product->ingredients as $productIngredient) {
            $quantityRequired = $productIngredient->pivot->quantity * $quantity;
            if ($quantityRequired > $productIngredient->quantity) {
                return \response(trans('Sorry, We don\'t have enough stock to complete your order'), 403);
            }
        }

        try {
            DB::transaction( function () use ($product, $quantity) {
                $order = Order::create([]);
                $orderProduct[$product->id] = ['quantity' => $quantity];
                $order->products()->sync($orderProduct);

                //Maintain the ingredient stock
                foreach ($product->ingredients as $productIngredient) {
                    $newQuantity = $productIngredient->quantity - ($productIngredient->pivot->quantity * $quantity);
                    $productIngredient->update([
                        'quantity' => $newQuantity
                    ]);
                    //Send a notification if ingredient quantity is less than 50%
                    $percentage = config('settings.ingredient_notification_quantity_in_percentage');
                    $percentageQuantity = ($productIngredient->in_stock_quantity * $percentage) / 100;
                    if ($productIngredient->quantity < $percentageQuantity &&
                        !$productIngredient->is_stock_notification_sent
                    ) {
                        $productIngredient->update([
                            'is_stock_notification_sent' => 1
                        ]);
                        IngredientStockNotificationEmailJob::dispatch($productIngredient);
                    }
                }
            });
        } catch (\Exception $exception) {
            return \response(trans('Something went wrong, Please try again'), 500);
        }

        return \response(trans('Order has been created successfully.'), 201);
    }
}
