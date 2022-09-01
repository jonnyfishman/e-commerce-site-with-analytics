<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;

use App\Models\Stock;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StockFactory extends Factory
{

    protected $fillable = ['shoe_size', 'stock_level'];

    public function definition()
    {
      return [
          'id' => Str::uuid(),
          'shoe_size' => 7,
          'stock_level' => random_int(35,156)
      ];
    }
}
