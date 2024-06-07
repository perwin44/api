<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\GeneralTrait;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class AssignGuard extends BaseMiddleware
{
    use GeneralTrait;
    //App\Http\Middleware\AssignGuard::returnError
    //use Illuminate\Support\Facades\Auth;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$guard=null): Response
    {
        if($guard!=null)
        {
            auth()->shouldUse($guard);//should you user guard/table
            $token=$request->header('auth-token');
            $request->headers->set('auth-token',(string)$token,true);
            $request->headers->set('Authorization','Bearer '.$token,true);
            try{
                //$user=$this->auth->authenticate($request);//check authenticated user
                $user=JWTAuth::parseToken()->authenticate();
            }catch(TokenInvalidException $e){
                return $this->returnError('401','Unauthenticated user');;
            }catch(JWTException $e){
                return $this->returnError('','Token Invalid',$e->getMessage());
            }
        }
        return $next($request);

        //return $next($request);
    }
}
