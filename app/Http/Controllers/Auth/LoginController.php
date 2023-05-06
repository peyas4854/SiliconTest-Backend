<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'is_admin' => 'nullable',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
//            'is_admin' => $request->is_admin ? 1:0,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json([
            'token' => $token,
            'message'=>'Registration Successfully'
        ], 200);
    }
    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Api Token')->accessToken;
            return response()->json([
                'user' => auth()->user(),
                'token' => $token,
                'expires_at'   => Carbon::now()->addWeeks(1)->toDateTimeString(),
                'message'=>'Login Successfully'
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();
        return response()->json(['status' => 'success', 'message' => 'Successfully logout',], 200);
    }
}
