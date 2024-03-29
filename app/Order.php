<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function products()
    {
      return $this->belongsTo(OrderProduct::class);
    }
}
