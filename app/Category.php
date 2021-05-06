<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\CategoryTransFormer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //softDeleteの追加
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //transformerの実装
    public $transformer = CategoryTransFormer::class;


    protected $fillable = [
        'name',
        'description'
    ];
    protected $hidden = [
        'pivot'
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
