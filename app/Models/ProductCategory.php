<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_code', 'code')->where('active', 1);
    }

}
