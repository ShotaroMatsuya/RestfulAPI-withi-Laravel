<?php

namespace App;

use App\Transaction;
use App\Scopes\BuyerScope;


class Buyer extends User
{
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
