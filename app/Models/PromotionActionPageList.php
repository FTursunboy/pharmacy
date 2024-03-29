<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionActionPageList extends Model
{
    protected $table = 'promotion_actions_page_list';

    public function product() {
        return $this->belongsTo(Product::class, 'code', 'product_code');
    }


}
