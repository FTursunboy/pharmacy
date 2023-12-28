<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = false;

    protected $table = 'products';

    public function property() {
        return $this->hasMany(ProductProperty::class, 'product_code', 'code');
    }

    public function image() {
        return $this->belongsTo(ProductImage::class, 'product_code', 'code');
    }

    public function promotionActionPageList() {
        return $this->belongsTo(PromotionActionPageList::class, 'product_code', 'code');
    }


}
