<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
  public function index(Request $request) {
/*
    $sort_parameters = ['name','brand','price','colour_index'];
//return ( in_array($request->query()['sortBy'].'_index', $sort_parameters) || in_array($request->query()['sortBy'], $sort_parameters) );
    if ( $request->has('sortBy') && in_array($request->query()['sortBy'], $sort_parameters) ) {
      if ( $request->has('desc') ) {
          return ProductResource::collection( Product::orderBy( $request->query()['sortBy'], 'desc' )->orderBy( 'name' )->get() );
      }
      else {
        return ProductResource::collection( Product::orderBy( $request->query()['sortBy'] )->orderBy( 'name' )->get() );
      }

    }
    else {
      return ProductResource::collection( Product::all() );
    }
  */
    return ProductResource::collection( Product::select('name','id','brand','colour','price','image')->get() );
  }

  public function show($id) {
      return new ProductResource( Product::findOrFail($id) );
  }
}
