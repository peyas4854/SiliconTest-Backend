<?php

namespace App\service;

use App\Models\Cart;

class CartService
{
    public function index($request)
    {


        return Cart::query()
            ->where('user_id', $request->user_id)
            ->orderBy('id', 'desc')
            ->get();

    }
}
