<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Units added when seeder run
     */
    CONST UNITS = [
      [
          'name' => 'kilogram',
          'short_name' => 'kg'
      ],
      [
          'name' => 'gram',
          'short_name' => 'g'
      ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::UNITS as $unit) {
            $isExist = Unit::where('name', $unit['name'])->first(); //NOTE:: We can also use updateOrCreate function

            if (empty($isExist)) {
                Unit::create([
                   'name' => $unit['name'],
                   'short_name' => $unit['short_name']
                ]);
            }
        }
    }
}
