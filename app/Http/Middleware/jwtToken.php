<?php

namespace App\Http\Middleware;
use App\Http\Traits\ApiDesignTrait;
use JWTAuth;
use Closure;

class jwtToken
{
    use ApiDesignTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {

               JWTAuth::parseToken()->authenticate();

        } catch(\Exception $e){

            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return $this->ApiResponse(422, 'this token expired');

            }elseif($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return $this->ApiResponse(422, 'this token invalid');

            }else {

                return $this->ApiResponse(404, 'Token not found');
            }

        } //end of try and catch


        return $next($request);
    }
}
