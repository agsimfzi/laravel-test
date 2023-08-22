<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return response($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response($user);
    }
}
