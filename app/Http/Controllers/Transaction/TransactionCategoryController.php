<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        //transactionに紐づくcategoryを取得するためには一度productを経由する必要がある
        $categories = $transaction->product->categories;
        return $this->showAll($categories);
    }
}
