<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class PermissionController extends Controller
{
    public function GetPermissions(){
        $data = $this->GetAllData(Permission::class);      
        return ResponseHelper::create_response($data, "Retrieved all available permissions", 200);
    }   

    public function GetPermissionById(Request $request){
        $validate = Validator::make($request->all(), [
            "id" => "required"
        ]);

        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);
        }else{
            $data =  $this->GetById(Permission::class, $request->id);
            if(!$data){
                return ResponseHelper::create_response($data, "Permission not found", 404);
            }else{
                return ResponseHelper::create_response($data, "Permission Found", 200);
            }
            
        }             
    }

    public function CreatePermission(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'guard_name' => 'nullable'
        ]);

        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);
        }else{
            $data = $this->CreateData($request, Permission::class);
            return ResponseHelper::create_response($data, "Permission created", 200);         
        }   
    }

    public function EditPermission(Request $request){
        $validate = Validator::make($request->all(), [
            "id" => "required",
            "name" => "nullable",
            'guard_name' => 'nullable'            
        ]);
        
        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);

        }else{
            $data = $this->EditData($request, Permission::class);
            return ResponseHelper::create_response($data, "Permission updated", 200);  
        }
    }

    public function DeletePermission(Request $request){
        $validate = Validator::make($request->all(), [
            'id' => 'required',            
        ]);

        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);

        }else{
            return $this->DeleteData(Permission::class, $request->id);            
        }   
    }
}
