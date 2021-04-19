<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

//in order to have a Scope , we need to implement an interface called 'Scope'
class BuyerScope implements Scope
{

    //Buyerインスタンスは常にtransactionsを持っている必要がある(自動的にqueryを追加してくれるglobal scopeを定義)
    public function apply(Builder $builder, Model $model)
    {
        $builder->has('transactions');
        //Buyerモデルにてbootメソッドを定義するのを忘れずに
    }
}
