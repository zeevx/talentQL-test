<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class verifyProductOwner
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('api')->user();
        $checkRole = $user->hasRole('product-owner');
        if(!$checkRole){
            return $this->error('You do not have access',401);
        }
        return $next($request);
    }
}
