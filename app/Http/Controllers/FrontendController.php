<?php

namespace App\Http\Controllers;

use App\Helpers\CartManager;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        return view('pages.index', ['count' => CartManager::count()]);
    }
}
