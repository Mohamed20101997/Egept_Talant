<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiDesignTrait;
use Closure;

class Roles
{
    use ApiDesignTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $userRole = auth()->user()->roleName->name;
        $allowRoles  = explode('.'  , $roles);

        if(! in_array($userRole , $allowRoles)){

            return $this->ApiResponse('422' , 'dont have permission');

        }


        return $next($request);
    }
}
