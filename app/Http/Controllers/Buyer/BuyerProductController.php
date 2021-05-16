<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
        $this->middleware('can:view,buyer')->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //以下のようにするとcollectionが返ってくるのでそれぞれのproduct属性にアクセスできない
        // $products = $buyer->transactions->product;
        // それぞれのtransactionのproduct属性にアクセスするためにはeagerLoadingを使用する
        //withメソッドを使用することでeager loadingが実装できdbの負荷を軽減できる

        // dd($buyer->transactions());
        // $products = $buyer->transactions()->with('product')->get();
        //上記のquery builderによりcollectionが返ってくるのでpluckメソッドを使うことができる
        $products = $buyer->transactions()->with('product')->get()->pluck('product');

        return $this->showAll($products);
    }
}
