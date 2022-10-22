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
    $sql = [];

    $validated = $request->validate([
      'range' => [new Range($categories)],
      'colour' => '',
      'brand' => 'regex:/^[a-zA-Z0-9,]*$/',
      'fit' => 'regex:/^[a-zA-Z0-9,]*$/',
      'rise' => 'regex:/^[a-zA-Z0-9,]*$/',
      'terrain' => 'regex:/^[a-zA-Z0-9,]*$/'
    ]);



    foreach ($request->all() as $category => $value) {
        if ( $category === 'range') {
          $range = explode(',',$value );

          if ( count( $range ) > 1 ) {
            for ($i=0; $i < count($range); $i+=3) {
              [$name,$min,$max] = [$range[$i],$range[$i+1],$range[$i+2]];

                array_push($sql, [$name, '>', $min], [$name, '<=', $max]);

            }
          }
        }
        else if ( !in_array($category, $categories) ) {

            //abort( response('Unauthorized', 401) );
            abort( 401 );
        } else if ($category !== 'range') {
          $values = explode(',',$value );
            foreach ($values as $value) {
              array_push($sql, [$category, 'like', '%'.$value.'%']);

            }

        }

    }

    $products = Product::where(function($query) use ($sql) {
        $query->where(array_splice($sql,0,1));
        foreach($sql as $q) {
          if ( $q[1] === '>' || $q[1] === '<=') {
              $query->Where([$q]);
          } else {
            $query->orWhere([$q]);
          }
        }
    })->join('product_information', 'products.id', '=', 'product_id')->pluck('products.id');

    return ['data' => $products];

  }

}
