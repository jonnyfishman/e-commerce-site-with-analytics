<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\ProductInformation;

class ProductInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
      Product::all()->each(function (Product $product) {
        $information = ProductInformation::factory()->make();


      $product->information()->save($information);

      });
    }
}
