<?php

namespace App;

use App\Transaction;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransFormer;


class Buyer extends User
{
    //transformerの実装
    public $transformer = BuyerTransFormer::class;

    //boot method basically executed when an instance of this model is created(Global scope)
    protected static function boot()
    {
        parent::boot(); //bootメソッドはmodelの初期化専用メソッド


        static::addGlobalScope(new BuyerScope);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
