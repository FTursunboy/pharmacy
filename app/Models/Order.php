<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    public function orderProducts()
    {
        return $this->hasMany(OrderProducts::class, 'order_id');
    }

    public function actionList(): HasMany
    {
        return $this->hasMany(PromotionActionPageList::class, 'order_id', 'id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class,'shop_code', 'code');
    }

    public function promotionProductCodes()
    {
        $validProductCodes = PromotionActionPageList::pluck('product_code')->toArray();

        return $this->orderProducts->whereIn('product_code', $validProductCodes)->pluck('product_code');
    }


    public function productCodes()
    {
        return $this->orderProducts->pluck('product_code');
    }
}
