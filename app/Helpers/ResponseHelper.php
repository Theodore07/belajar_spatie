<?php
namespace App\Helpers;


Class ResponseHelper{
    
    public static function create_response($data, $msg, $status){       
        return response()->json(['message' => $msg, 'data' => $data], $status);
    }

}