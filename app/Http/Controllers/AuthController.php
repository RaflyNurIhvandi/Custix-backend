<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'=>'error',
                'message'=>'Login gagal!'
            ], 401);
        }
        $request->session()->regenerate();
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'akses' => Auth::user()->akses
        ]);
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    public function user(Request $request) {
        return response()->json([
            'data'=>$request->user()
        ]);
    }
    public function akses() {
        $data = DB::table('users')->get();
        return response()->json($data);
    }
}
