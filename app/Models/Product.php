<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public static $categorical = ['brand','colour'];

    public function stock() {
      return $this->hasMany(Stock::class);
    }

    public function information() {
      return $this->hasOne(ProductInformation::class);
    }

}
