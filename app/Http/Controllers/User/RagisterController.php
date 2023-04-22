<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash ; 
use Illuminate\Http\Request;
use App\Helpers\Helper; 
use Illuminate\Support\Facades\Validator;
class RagisterController extends Controller
{
    //

    public function ragister(Request $request){

        try{
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email:rfc',
                    'password'=>'required|min:6|max:10',
                    'number'=> 'required|integer|min:10'
                ]);
          
    
            if ($validator->fails()) {
                $info['status'] = 400;
                $info['data']   = '';
                $info['msg']    = $validator->messages()->first();;
    
                return response()->json($info);
            } 
        $userInsert = new User ; 
        $userInsert->name = $request->name ;
        $userInsert->email = $request->email;
        $userInsert->password  = Hash::make($request->password);
        $userInsert->number = $request->number ; 
        $userInsert->save(); 
        $data = [] ;
        $data['name'] = $userInsert->name ; 
        $data['email'] =  $userInsert->email ;
        $data['number'] =  $userInsert->number ;
        $message = "Success Register " ; 
        $helper = Helper::getSuccessResponseData($data,$message) ;
        return response()->json($helper);

    }catch(Exception $e){
        $info['status'] = 400;
        $info['msg']    = $e->getMessage();
        return response()->json($info);
    }
       
    }

    public function login( Request $request){
        try{
            $validator = Validator::make($request->all(), [
                        'email' => 'required|email:rfc',
                        'password'=>'required|min:6|max:10'
                    ]);
                if ($validator->fails()) {
                    $info['status'] = 400;
                    $info['data']   = '';
                    $info['msg']    = $validator->messages()->first();;
        
                    return response()->json($info);
                } 

                $userCheck = User::where('email',$request->email)->first();
                if(empty($userCheck)){
                  $info['status'] = 400;
                  $info['register_status'] = 0;
                  $info['msg']    = 'Invalid Login Details';
                  return response()->json($info);
                }
 
            $passwordCheck = Hash::check($request->password, $userCheck->password);
            if(!$passwordCheck){
                $info['status'] = 400;
                $info['register_status'] = 0;
                $info['msg']    = 'Invalid Login Details';
                return response()->json($info);
            }
            $token =  $userCheck->createToken('auth_token')->plainTextToken;
            $userCheck->remember_token = $token;
            $userCheck->update();

            $data = [] ;
            $data['name'] = $userCheck->name ; 
            $data['email'] =  $userCheck->email ;
            $data['token']= $userCheck->remember_token ; 
            $data['number'] =  $userCheck->number ;
            $message = "Success Register " ; 
            $helper = Helper::getSuccessResponseData($data,$message) ;
            return response()->json($helper);

            }catch(Exception $e){
                $info['status'] = 400;
                $info['msg']    = $e->getMessage();
                return response()->json($info);
            }

    }
}
