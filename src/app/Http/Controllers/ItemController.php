<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品一覧画面
    public function index()
    {
        return view('items.index');
    }

    // 商品出品画面
    public function showExhibition()
    {
        return view('items.exhibition');
    }
}
