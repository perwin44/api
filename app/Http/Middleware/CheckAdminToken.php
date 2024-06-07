<?php

namespace App\Http\Middleware;

use App\Http\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdminToken
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=null;
        try{
            $user=JWTAuth::parseToken()->authenticate();
        }catch(\Exception $e){
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                //return response()->json(['success'=>false,'msg'=>'INVALID_TOKEN'],status:200);
                return $this -> returnError('E3001','INVALID_TOKEN');

            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                //return response()->json(['success'=>false,'msg'=>'EXPIRED_TOKEN'],status:200);
                return $this -> returnError('E3001','EXPIRED_TOKEN');

            }else{
                //return response()->json(['success'=>false,'msg'=>'TOKEN_NOTFOUND'],status:200);
                return $this -> returnError('E3001','TOKEN_NOTFOUND');
            };
        }catch(\Throwable $e){
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                //return response()->json(['success'=>false,'msg'=>'INVALID_TOKEN'],status:200);
                return $this -> returnError('E3001','INVALID_TOKEN');

            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                //return response()->json(['success'=>false,'msg'=>'EXPIRED_TOKEN'],status:200);
                return $this -> returnError('E3001','EXPIRED_TOKEN');

            }else{
                //return response()->json(['success'=>false,'msg'=>'TOKEN_NOTFOUND'],status:200);
                return $this -> returnError('E3001','INVALID_TOKEN');
            };
        }
        if(!$user)
        //return response()->json(['success'=>false,'msg'=>trans('unauthenticated')],status:200);
        return $this-> returnError('',trans('unauthenticated'));


        return $next($request);
    }
}
