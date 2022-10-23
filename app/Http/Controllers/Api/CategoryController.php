<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Resources\ProductInformationResource;

use Illuminate\Support\Collection;
use App\Rules\Range;

Collection::macro('ksort', function(){
   //macros callbacks are bound to collection so we can safely access
   // protected Collection::items
   ksort($this->items, SORT_NATURAL | SORT_FLAG_CASE);  // make uasort to reorder nmbers

   return $this;
   //to return a new instance
   //return collect($this->items);
});

class CategoryController extends Controller
{
  public function index(Request $request) { // should have one route to get categories then another to get category ids

    $categories = ['colour','brand','fit','rise','terrain','price']; // push this to factory
    $query = [];

    //need validation

    foreach ($request->all() as $category => $value) {    // HELPER FUNCTION?
        if ( !in_array($category, $categories) ) {

            //abort( response('Unauthorized', 401) );
            abort( 401 );
        } else {
          $values = explode(',',$value );
            if ( in_array('range',$values) ) {

                [$min,$max] = [$values[1],$values[2]];
                array_push($query, [$category, '>', $min], [$category, '<=', $max]);


            } else {
              foreach ($values as $value) {
                array_push($query, [$category, 'like', '%'.$value.'%']);

              }
            }


        }

    }


    $products = ProductInformationResource::collection( Product::select('products.id','price','colour','brand','product_information.fit','product_information.rise','product_information.terrain')->join('product_information', 'products.id', '=', 'product_id')->where($query)->get() );


    $c = collect($categories)->map(function ($name) use($products) {

      return [
              'name'=> $name,
              'values' => $products->mapToGroups(function ($item, $key) use($name) {
                                      //print_r($item).PHP_EOL;
                                      // if ( is_numeric($item[$name]) ) return [ $item[$name] => $item['id'] ];

                                      return [ collect($item)[$name] => $item['id'] ];
                                    })
                                    ->ksort()
                                    /*
                                    ->map(function ($item, $key) {

                                        return ['sub_category'=>$key, 'values'=>$item];
                                    })
                                    ->values()
                                    ->all()
                                    */

            ];
    });

    return ['data' => $c];
    /*
    $x = 1;

    foreach ($products as $product) { // Enable chunking?

        $y = 0;
        foreach ((array)$product as $category_name => $category_value) {

          if ( $category_name === 'id' ) continue;
          // isset( $categories[$category_name][$category_value] ) ? $categories[$category_name][$category_value]++ : $categories[$category_name][$category_value] = 1;

          if ( !isset($categories[$y]) ) $categories[$y] = [];
          if ( !isset($categories[$y]['name'][$category_name]) ) $categories[$y]['name'] = $category_name;
          if ( !isset($categories[$y]['values']) ) $categories[$y]['values'] = [];
          if ( !isset($categories[$y]['values'][$category_value]) ) $categories[$y]['values'][$category_value] = [];

          array_push($categories[$y]['values'][$category_value], $product['id'] );


          // isset( $categories[$category_name][$category_value] ) ? array_push($categories[$category_name][$category_value], $product['id']) : $categories[$category_name][$category_value] = [$product['id']];
          print_r($categories[$y]['values']);
          if ( count($products) == $x ) ksort($categories[$y]['values']);
          $y++;
        }

        $x++;
    }
    return ['data' => $categories];
    */
/*
    $p = [];

    foreach ( Product::$categorical as $column ) {

      // return Product::select($column, DB::raw('count('.$column.') AS total'))->groupBy($column)->orderBy($column)->get();
      array_push($p, Product::select($column, DB::raw('count('.$column.') AS total'))->groupBy($column)->orderBy($column)->get() );
    }

    foreach ( ProductInformation::$categorical as $column ) {

      // return Product::select($column, DB::raw('count('.$column.') AS total'))->groupBy($column)->orderBy($column)->get();
      array_push($p, ProductInformation::select($column, DB::raw('count('.$column.') AS total'))->groupBy($column)->orderBy($column)->get() );
    }

    return json_encode(['data'=>$p]);
*/
  }

}
