<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class IngredientsSeeder extends Seeder
{
    const INGREDIENTS = [
        [
            'name' => 'beef',
            'quantity' => 20,
            'unit' => 'gram',
            'quantity_in' => 'kilogram'
        ],
        [
            'name' => 'cheese',
            'quantity' => 5,
            'unit' => 'gram',
            'quantity_in' => 'kilogram'
        ],
        [
            'name' => 'onion',
            'quantity' => 1,
            'unit' => 'gram',
            'quantity_in' => 'kilogram'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::INGREDIENTS as $ingredient) {
            $isExist = Ingredient::where('name', $ingredient['name'])->first();

            if (empty($isExist)) {
                $quantity = ($ingredient['quantity_in'] === 'kilogram') ? $ingredient['quantity'] * 1000 : $ingredient['quantity'];
                $unit = Unit::where('name', $ingredient['unit'])->first();
                if (!empty($unit)) {
                    Ingredient::create([
                        'name' => $ingredient['name'],
                        'quantity' => $quantity,
                        'unit_id' => $unit->id,
                        'in_stock_quantity' => $quantity
                    ]);
                }
            }
        }
    }
}
