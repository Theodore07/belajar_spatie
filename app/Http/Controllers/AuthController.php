<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function APILogin(Request $request){
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
             ], 401);
        }else{
            $email = $request->email;
            $password = $request->password;

            if(!Auth::attempt(['email' => $email, 'password' => $password])){
                return response()->json([
                    'success' => false,
                    'message' =>  "Invalid Username or Password",
                    'data' => []
                 ], 401);
            }

            try{
                
                $user = Auth::user();
        
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
        
                $token->expires_at = Carbon::now()->addDay(1);
                $token->save();
        
                $success = true;
                $messages = 'Login successfully';
                $data = [
                    'access_token' => $tokenResult->accessToken,
                    'tokeny_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                ];
                $code = 200;
               }    
               catch(\Exception $e){
                $success = false;
                $messages = $e->getMessage();
                $data = [];
                $code = 500;
               }
               finally{
                return response()->json([
                    'success' => $success,
                    'messages' => $messages,
                    'data' => $data,
                ], $code);
               } 
        }      

    }

    public function APILogout(){
        $user = Auth::user();      
        if (!$user) {
            return response()->json([
                'success' => false,
                'messages' => 'User not authenticated',
            ], 401);
        }

        try {            
            $userToken = Auth::user()->token();   
            $userToken->revoke();
            
            $success = true;
            $messages = "Token Revoked";
            $data = $userToken;
            $code = 200 ;
           }
           catch(\Exception $e){
            $success = false;
            $messages = $e->getMessage();
            $data = [];
            $code = 500;
           }
           finally{
            return response()->json([
                'success' => $success,
                'messages' => $messages,
                'data' => $data,
            ], $code);
           }       
    }
}
