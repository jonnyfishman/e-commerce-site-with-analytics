<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductInformation>
 */
class ProductInformationFactory extends Factory
{

    protected $fit = [ 'narrow', 'wide' ];

    protected $terrain = [ 'road', 'trail' ];

    protected $material = [ 'carbon', 'rubber', 'foam', 'vibram sole', 'mesh upper', 'mono mesh' ];

    protected $tags = [ 'exaggerated forefoot', 'lightweight', 'breathable', 'cushioned', 'reflective', 'adaptive fit' ];

    private function arrayToJson($array, $minimum_number_of_options) {
      $shuffled = Arr::shuffle($array);
      $shortened = array_slice( $shuffled, 0, random_int($minimum_number_of_options, count($shuffled) ) );

      return json_encode($shortened);
    }

    public function definition()
    {
        return [
            'fit' => Arr::random($this->fit),
            'rise' => random_int(6,11),
            'terrain' => Arr::random($this->terrain),
            'material' => $this->arrayToJson($this->material, 3),
            'tags' => $this->arrayToJson($this->tags, 4)
        ];
    }

}
