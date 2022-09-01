<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Stock extends Model
{
    use HasFactory;

    public function product() {
      return $this->belongsTo(Product::class);
    }

    public function getIncrementing() {
        return false;
    }

    public function getKeyType() {
        return 'string';
    }
}
