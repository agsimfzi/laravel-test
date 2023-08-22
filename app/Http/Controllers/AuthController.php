<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi'
        ]);

        if (! $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            abort(401, 'Email atau password salah');
        }

        return response([
            'user' => auth()->user(),
            'token' => $this->respondWithToken($token)->original,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.same' => 'Konfirmasi Password tidak sesuai',
            'password.min' => 'Password setidaknya harus 8 karakter',
            'password_confirmation.required' => 'Konfirmasi Password harus diisi',
            'password_confirmation.min' => 'Konfirmasi Password setidaknya harus 8 karakter',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = JWTAuth::fromUser($user);

        return response([
            'message' => 'Pendaftaran berhasil',
            'user' => $user,
            'token' => $this->respondWithToken($token)->original,
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Berhasil log out']);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
