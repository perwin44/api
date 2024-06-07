<?php

namespace App\Http\Controllers\Api\Admin;

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

    use GeneralTrait;
    //
    public function login(Request $request){
        //validation

        try{
        $rules=[
            "email"=>"required|exists:admins,email",
            "password"=>"required"
        ];

        $validator=Validator::make($request->all(),$rules);

        if($validator->fails()){
            $code=$this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }



        //login
        $credentials=$request->only(['email','password']);
        $token=Auth::guard('admin-api')->attempt($credentials);

        if(!$token)
        return $this->returnError('E001','not found');


        $admin=Auth::guard('admin-api')->user();
        $admin->api_token=$token;
        //return token
        return $this->returnData('admin',$admin);

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
