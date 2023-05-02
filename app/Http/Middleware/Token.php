<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $array = User::query()->where("access_token", "=", $request->headers->all()["token"][0])->get();

        if(count($array) !== 0) {
            return $next($request);
        } else {
            return response(["error" => "not authorized"], 403);
        }
    }
}
