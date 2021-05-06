<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransFormer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    //softDeleteの追加
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //transformerの実装
    public $transformer = TransactionTransFormer::class;


    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
