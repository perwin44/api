<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    //use JWTAuth;
    use GeneralTrait;
    //
    public function UserLogin(Request $request){
        //validation

        try{
        $rules=[
            "email"=>"required",
            "password"=>"required"
        ];

        $validator=Validator::make($request->all(),$rules);

        if($validator->fails()){
            $code=$this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }



        //login
        $credentials=$request->only(['email','password']);
        $token=Auth::guard('user-api')->attempt($credentials);//generate token

        if(!$token)
        return $this->returnError('E001','not found');


        $user=Auth::guard('user-api')->user();
        $user->api_token=$token;
        //return token
        return $this->returnData('user',$user);//return json response

    }catch(\Exception $ex){
        return $this->returnError($ex->getCode(),$ex->getMessage());
    }

    }

    public function logout(Request $request){

        //$request->email;
        $token=$request->header('auth-token');

        if($token){
            try{
            JWTAuth::setToken($token)->invalidate();//logout
            }catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->returnError('E009','something went wrong');
            }
            return $this->returnSuccessMessage('logout successed');

        }else{
            $this->returnError('E009','something went wrong');
        }
    }
}
