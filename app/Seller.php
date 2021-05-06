<?php

namespace App;

use App\Product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransFormer;

class Seller extends User
{

    //transformerの実装
    public $transformer = SellerTransFormer::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SellerScope);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
