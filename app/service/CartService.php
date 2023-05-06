<?php

namespace App\service;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    public function index($request)
    {
        return Cart::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function store($request)
    {

        $cart = Cart::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'user_id' => $request->user_id,
            ],
            [
                'product_id' => $request->product_id,
                'user_id' => $request->user_id,
                'quantity' => $request->quantity,
            ]
        );
        return $cart;
    }

    public function incrementDecrement($request)
    {

        $cart = Cart::query()->where('product_id', $request->product_id)
            ->where('user_id', auth()->id())->first();

        $cart->update([
            'quantity' => $request->quantity
        ]);
        return $cart;
    }
    public function checkout(){
        Cart::query()
            ->where('user_id', auth()->id())
            ->delete();
    }
}
