<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Brand extends Model
{
    public function products()
    {
      return $this->belongsTo(Product::class);
    }
}
