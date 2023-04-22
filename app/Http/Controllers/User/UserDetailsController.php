<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserdetailsModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper; 
use Illuminate\Support\Facades\Validator;
use File;
class UserDetailsController extends Controller
{
   
    public function store(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'gender' => 'required',
                'hobbis'=>'required',
                'address'=>'required',
                'image'=>'required',
            ]);
            if ($validator->fails()) {
                $info['status'] = 400;
                $info['data']   = '';
                $info['msg']    = $validator->messages()->first();
                
                return response()->json($info);
            } 



        $loginUser = Auth::user() ; 
        $storeData = new UserdetailsModal ;
        $storeData->user_id = $loginUser->id; 
        $storeData->gender = $request->gender ; 
        $implodeHobbis = implode(',',$request->hobbis);
        $storeData->hobbis =$implodeHobbis; 
        $storeData->address = $request->address ;
       
        if (!empty($request->image)) {
			$file_data = $request->input('image');
			$file_name = 'user-' . time() . '.png';
			@list($type, $file_data) = explode(';', $file_data);
			@list(, $file_data)= explode(',', $file_data);
			if ($file_data != "") {
			$folder = public_path() . '/uploads/user_' . $loginUser->id;

				if (!File::exists($folder)) {
					File::makeDirectory($folder, 0775, true, true);
				}
				file_put_contents($folder . '/' . $file_name, base64_decode($file_data));
			}
			$profileImage = asset('uploads/user_' . $loginUser->id . '/' . $file_name);
            $storeData->image = $file_name ;
		}
        $storeData->save();

        $data = [] ;
        $data['name'] = $loginUser->name ; 
        $data['email'] =  $loginUser->email ;
        $data['number'] =  $loginUser->number ;
        if($storeData->gender == 0  )
        {
            $data['gender'] = 'male';
        }else{
            $data['gender'] = 'female';
        }   
        $data['profile_image'] = $profileImage  ;
        $data['hobbis'] = explode(',',$storeData->hobbis);
        $data['address'] = $storeData->address;


        $message = "Success Register " ; 
        $helper = Helper::getSuccessResponseData($data,$message) ;
        return response()->json($helper);

      
        }catch(Exception $e){
            $info['status'] = 400;
            $info['msg']    = $e->getMessage();
            return response()->json($info);
        }
    }

    public function getUserDetails(){
        try{
        $loginUser = Auth::user() ; 
        $storeData =  UserdetailsModal::where('user_id',$loginUser->id)->get();
        $data = [] ;
        $data['id'] = $loginUser->id ; 
        $data['name'] = $loginUser->name ; 
        $data['email'] =  $loginUser->email ;
        $data['number'] =  $loginUser->number ;
        $userDetails = []; 
        foreach($storeData as $key=>$value){
            $userDetails[$key]['id'] = $value->id; 
            if($value->gender == 0  )
            {
                $userDetails[$key]['gender'] = 'male';
            }else{
                $userDetails[$key]['gender'] = 'female';
            } 
            $profileImage = asset('uploads/user_' . $loginUser->id . '/' .  $value->image);
            $userDetails[$key]['profile_image'] = $profileImage  ;
            $userDetails[$key]['hobbis'] = explode(',',$value->hobbis);
            $userDetails[$key]['address'] = $value->address;
        }
        $data['userdetails'] = $userDetails ;
        $message = "Success Register " ; 
        $helper = Helper::getSuccessResponseData($data,$message) ;
        return response()->json($helper);
        }catch(Exception $e){
            $info['status'] = 400;
            $info['msg']    = $e->getMessage();
            return response()->json($info);
        }
    }
    public function edit($id){
        try{
            if (empty($id)) {
                $info['status'] = 400;
                $info['data']   = '';
                $info['msg']    = 'Id is required.';
                return response()->json($info);
            } 
            
        $loginUser = Auth::user(); 
        $storeData =  UserdetailsModal::where('user_id',$loginUser->id)->where('id',$id)->first();
        $data = [] ;
        $data['id'] = $loginUser->id ; 
        $data['name'] = $loginUser->name ; 
        $data['email'] =  $loginUser->email ;
        $data['number'] =  $loginUser->number ;

        $userDetails = []; 
    if(!empty( $storeData)){
            $userDetails['id'] = $storeData->id; 
            if($storeData->gender == 0  )
            {
                $userDetails['gender'] = 'male';
            }else{
                $userDetails['gender'] = 'female';
            } 
            $profileImage = asset('uploads/user_' . $loginUser->id . '/' .  $storeData->image);
            $userDetails['profile_image'] = $profileImage  ;
            $userDetails['hobbis'] = explode(',',$storeData->hobbis);
            $userDetails['address'] = $storeData->address;
        }else{
            $userDetails[] = 'Data Not Exist';
        }
      
        $data['userdetails'] = $userDetails ;
        $message = "Success Register " ; 
        $helper = Helper::getSuccessResponseData($data,$message) ;
        return response()->json($helper);
        }catch(Exception $e){
            $info['status'] = 400;
            $info['msg']    = $e->getMessage();
            return response()->json($info);
        }

    }

    public function update(Request $request){
        try{
          
            $validator = Validator::make($request->all(), [
                'gender' => 'required',
                'hobbis'=>'required',
                'address'=>'required',
            ]);
            if ($validator->fails()) {
                $info['status'] = 400;
                $info['data']   = '';
                $info['msg'] = $validator->messages()->first();
                
                return response()->json($info);
            } 

        $loginUser = Auth::user(); 
        $storeData =  UserdetailsModal::where('user_id',$loginUser->id)->where('id',$request->id)->first();
        $data = [] ;
        $data['id'] = $loginUser->id ; 
        $data['name'] = $loginUser->name ; 
        $data['email'] =  $loginUser->email ;
        $data['number'] =  $loginUser->number ;
        $userDetails = []; 
        
        if(!empty( $storeData)){
            $storeData->gender = $request->gender ; 
            $implodeHobbis = implode(',',$request->hobbis);
            $storeData->hobbis =$implodeHobbis; 
            $storeData->address = $request->address ;
           
            if (!empty($request->image)) {
                $file_data = $request->input('image');
                $file_name = 'user-' . time() . '.png';
                @list($type, $file_data) = explode(';', $file_data);
                @list(, $file_data)= explode(',', $file_data);
                if ($file_data != "") {
                $folder = public_path() . '/uploads/user_' . $loginUser->id;
    
                    if (!File::exists($folder)) {
                        File::makeDirectory($folder, 0775, true, true);
                    }
                    file_put_contents($folder . '/' . $file_name, base64_decode($file_data));
                }
                $profileImage = asset('uploads/user_' . $loginUser->id . '/' . $file_name);
                $storeData->image = $file_name ;
            }
            $storeData->save();
            $userDetails['id'] = $storeData->id; 
            if($storeData->gender == 0  )
            {
                $userDetails['gender'] = 'male';
            }else{
                $userDetails['gender'] = 'female';
            } 
            $profileImage = asset('uploads/user_' . $loginUser->id . '/' .  $storeData->image);
            $userDetails['profile_image'] = $profileImage  ;
            $userDetails['hobbis'] = explode(',',$storeData->hobbis);
            $userDetails['address'] = $storeData->address;
        }else{
            $userDetails[] = 'Data Not Exist';
        }
        
        $data['userdetails'] = $userDetails ;
        $message = "Success Register " ; 
        $helper = Helper::getSuccessResponseData($data,$message) ;
        return response()->json($helper);
        }catch(Exception $e){
            $info['status'] = 400;
            $info['msg']    = $e->getMessage();
            return response()->json($info);
        }

    }

    public function delete($id){

        $storeData =  UserdetailsModal::where('id',$id)->delete();
        $data = [] ;
        if(!empty( $storeData)){
            $message = "Success Deleted successfully " ; 
        }else{
            $message = "Data Not Found !" ; 
        }

        $helper = Helper::getSuccessResponseData($data,$message) ;
        return response()->json($helper);
    }


}
