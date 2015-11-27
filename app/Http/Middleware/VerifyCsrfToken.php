<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Input;
use Session;
use Redirect;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    	'admin/login',
        'admin/catlog/addcatlog',
        'admin/catlog/updateCatlog/{id}',        
    ];


     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        // if ($this->isReading($request) 
        //     || $this->excludedRoutes($request) 
        //     || $this->tokensMatch($request))
        // {
        //     return $this->addCookieToResponse($request, $next($request));
        // }

    	if( Session::token() == Input::get('_token') ){
        	return $next($request);
        }

        return Redirect::back();

        //throw new \TokenMismatchException;
    }

    /**
     * This will return a bool value based on route checking.
     * @param  Request $request
     * @return boolean
     */
    protected function excludedRoutes($request)
    {
        foreach($this->except as $route)
            if ($request->is($route))
                return true;

            return false;
    }

    /**
     * This will return a bool value based on route checking.
     * @return boolean and back to request url
     */
    // public function verifyFormToken(){
    // 	if( Session::token() == Input::get('_token') ){
    //     	return $next($request);
    //     }

    //     return Redirect::back();
    // }
}
