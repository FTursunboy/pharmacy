<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProducts extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'code');
    }
}
