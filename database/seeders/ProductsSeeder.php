<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Products added to system when seeder will run
     */
    CONST PRODUCTS = [
        [
            'name' => 'burger',
            'ingredients' => [
                [
                    'name' => 'beef',
                    'quantity' => 150
                ],
                [
                    'name' => 'cheese',
                    'quantity' => 30
                ],
                [
                    'name' => 'onion',
                    'quantity' => 20
                ]
            ]
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::PRODUCTS as $product) {
            $isExists = Product::where('name', $product['name'])->first();

            if (empty($isExists)) {
                $productObject = Product::create([
                   'name' => $product['name']
                ]);

                $productIngredientIds = [];
                foreach ($product['ingredients'] as $productIngredient) {
                    $ingredient = Ingredient::where('name', $productIngredient['name'])->first();
                    if (!empty($ingredient)) {
                        $productIngredientIds[$ingredient->id] = ['quantity' => $productIngredient['quantity']];
                    }
                }
                $productObject->ingredients()->sync($productIngredientIds);
            }
        }
    }
}
