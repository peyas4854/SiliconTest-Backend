<?php

namespace App\Http\Controllers;

use App\service\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;

    }

    public function index(Request $request)
    {

        return $this->cartService->index($request);
    }
}
