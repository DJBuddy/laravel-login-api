<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
class UserController extends Controller
{
    //
    public $successStatus = 200;

    public function login(Request $request){ 

        if(Auth::guard()->attempt(['email' =>$request->email, 'password' => $request->password])){ 
            $admin = Auth::guard()->user(); 
             $success['token'] =  $admin->createToken('MyApp')->accessToken; 
             return response()->json(['success' => $success], $this->successStatus); 
         } 
         else{ 
             return response()->json(['error'=>'invalid credentials'], 401);  
         } 
    }

        /** 
         * Register api 
         * 
         * @return \Illuminate\Http\Response 
         */ 
        public function register(Request $request) 
        { 
            $validator = Validator::make($request->all(), [ 
                'fullname' => 'required',
                'phone' => 'required', 
                'email' => 'required|email', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
                $input = $request->all(); 
                $input['password'] = bcrypt($input['password']); 
                $user = User::create($input); 
                $success['token'] =  $user->createToken('MyApp')-> accessToken; 
                $success['fullname'] =  $user->fullname;
                return response()->json(['success'=>$success], $this-> successStatus); 
      }
        /** 
         * details api 
         * 
         * @return \Illuminate\Http\Response 
         */ 
        public function details() 
        { 
            
            $user = Auth::guard('userApi')->user(); 
            return response()->json(['success' => $user], $this-> successStatus); 

        
        } 


}
