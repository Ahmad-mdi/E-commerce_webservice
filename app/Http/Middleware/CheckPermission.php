<?php

namespace App\Http\Middleware;

use App\Trait\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next ,$permission)
    {
        if(!auth()->user()->role->hasPermission($permission)){
            return $this->errorResponse(403,'not permission user!');
        }
        return $next($request);
    }
}
