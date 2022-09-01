<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\Stock;

class StockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Product::all()->each(function (Product $product) {
          $stock = Stock::factory()->make();
          $all_stock = collect([$stock]);

          for ($x = 8; $x <= 14; $x++) {
            $size = (clone $stock)->shoe_size + 1;

            $stock = Stock::factory()->make();
            $stock->shoe_size = $size;

            $all_stock->push($stock);
          }

        $product->stock()->saveMany($all_stock);

        });

    }
}
