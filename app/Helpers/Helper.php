<?php

namespace App\Helpers;

 class Helper {

    public function getSuccessResponseData($data, $message){
        
        $result = [] ; 
        if($message != ''){
        $result['status'] = 200 ;
        $result['data'] = $data;
        $result['message'] = $message; 
        }else{
        $result['status'] = 200 ;
        $result['data'] = $data;
        $result['message'] = 'Successfull'; 
        }
        return $result;
    }

    public function getErrorResponseData($data , $message){

        $result = [] ; 
            if($message != ''){
            $result['status'] = 400 ;
            $result['message'] = $message; 
            }else{
            $result['status'] = 400 ;
            $result['message'] = 'Some Thing Wrong '; 
            }
            return $result;
    }
}

?>