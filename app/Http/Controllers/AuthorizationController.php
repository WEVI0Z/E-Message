<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
    function login(Request $request) {
        $request->validate([
            "name" => "required",
            "password" => "required",
        ]);
    }

    function register(Request $request) {
        $request->validate([
            "name" => "required",
            "password" => "required|min:8",
            "password-repeat" => "required",
        ]);
    }
}
