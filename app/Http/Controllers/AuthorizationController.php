<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{
    function loginApi(Request $request) {
        $validator = Validator::make($request->all(), [
            "login" => "required",
            "password" => "required",
        ]);

        if($validator->fails()) {
            return response([
                'status' => 'Validator exception',
                'errors' => $validator->errors()
            ], 400);
        }

        return response(
            ["access_token" => User::query()
                ->where("login", "=", $request->login)
                ->where("password", "=", $request->password)
                ->get()[0]->access_token
            ], 200
        );
    }

    function registerApi(Request $request) {
        $validator = Validator::make($request->all(), [
            "login" => "required|unique:users",
            "password" => "required|min:8",
        ]);

        if($validator->fails()) {
            return response([
                'status' => 'Validator exception',
                'errors' => $validator->errors()
            ], 400);
        }

        $token = Hash::make($request->login);

        User::create([
            "login" => $request->login,
            "password" => $request->password,
            "access_token" => $token
        ]);

        return response(["access_token" => $token], 200);
    }

    function tokenTest(Request $request) {
        return User::query()->where("access_token", "=", $request->headers->all()["token"][0])->get();
    }
}
