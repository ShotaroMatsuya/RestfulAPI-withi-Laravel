<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //collapseメソッドはnested array を一つに展開してくれる
        //value メソッドでuniqueメソッドによりrepeatされてしまったempty dataを削除してくれる
        $sellers = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()
            ->unique('id')
            ->values();
        return $this->showAll($sellers);
    }
}
