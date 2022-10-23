<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

use App\Rules\Range;

Collection::macro('ksort', function(){
   //macros callbacks are bound to collection so we can safely access
   // protected Collection::items
   ksort($this->items, SORT_NATURAL | SORT_FLAG_CASE);  // make uasort to reorder nmbers

   return $this;
   //to return a new instance
   //return collect($this->items);
});

class ProductIdController extends Controller
{
  public function index(Request $request) { // rename some attributers i.e. colour to get column value

    $categories = ['colour','brand','fit','rise','terrain','price']; // push this to factory

    $validated = $request->validate([
      'range' => [new Range($categories)],
      'colour' => '',
      'brand' => 'regex:/^[a-zA-Z0-9,]*$/',
      'fit' => 'regex:/^[a-zA-Z0-9,]*$/',
      'rise' => 'regex:/^[a-zA-Z0-9,]*$/',
      'terrain' => 'regex:/^[a-zA-Z0-9,]*$/'
    ]);

    $and = [];
    $or = [];

    foreach ($request->all() as $category => $value) {    // HELPER FUNCTION?
        if ( !in_array($category, $categories) ) {

            //abort( response('Unauthorized', 401) );
            abort( 401 );
        } else {
          $values = explode(',',$value );
            if ( in_array('range',$values) ) {

                [$min,$max] = [$values[1],$values[2]];
                array_push($and, [$category, '>', $min], [$category, '<=', $max]);


            } else {
              foreach ($values as $value) {
                array_push($or, [$category, 'like', '%'.$value.'%']);

              }
            }


        }

    }

    $products = Product::where(function($query) use ($and) {
        foreach($and as $sql) {
              $query->Where([$sql]);
        }
    })->where(function($query) use ($or) {
        foreach($or as $sql) {
            $query->orWhere([$sql]);
        }
    })->join('product_information', 'products.id', '=', 'product_id')->pluck('products.id');

    return ['data' => $products];

  }

}
