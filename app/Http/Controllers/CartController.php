<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\CartResource;
use App\service\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseController
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;

    }

    public function index(Request $request)
    {

        $cart = $this->cartService->index($request);
        return CartResource::collection($cart);
    }

    public function store(Request $request)
    {

        $cart = $this->cartService->store($request);

        return $this->returnResponse('success', 'cart store  successfully', $cart, 200);
    }

    public function changeQuantity(Request $request)
    {
        $cart = $this->cartService->incrementDecrement($request);

        return $this->returnResponse('success', 'Quantity Update successfully', $cart, 200);
    }

    public function checkout()
    {
        $this->cartService->checkout();
        return $this->returnResponse('success', 'Checkout successfully', [], 200);

    }
}
