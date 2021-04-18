<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class ApiController extends Controller
{
    //we can use every method in the ApiResponser directly in our controller
    use ApiResponser;
}
