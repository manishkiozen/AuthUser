<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::get('_admin_token')){    
            return $next($request);
        }

        return redirect('admin');
    }
}
