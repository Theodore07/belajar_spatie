<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\ResponseHelper;


class RoleController extends Controller
{
    public function GetRoles(){
        $data = $this->GetAllData(Role::class);         
        return ResponseHelper::create_response($data, "Retrieved all available roles", 200);
    }

    public function GetRoleById(Request $request){
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
            $data = $this->GetById(Role::class, $request->id);
            if(!$data){
                return ResponseHelper::create_response($data, "Role not found", 404);
            }else{
                return ResponseHelper::create_response($data, "Role Found", 200);
            }
        }             
    }

    public function CreateRoles(Request $request){
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
            $data = $this->CreateData($request, Role::class); 
            return ResponseHelper::create_response($data, "Role created successfully", 200);            
        }   
    }

    public function EditRole(Request $request){
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
            $data = $this->EditData($request, Role::class);
            return ResponseHelper::create_response($data, "Role Updated", 200);
        }
    }

    public function DeleteRoles(Request $request){
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
            return $this->DeleteData(Role::class, $request->id);            
        }   
    }  
    
    public function AssignPermission(Request $request){
        $validate = Validator::make($request->all(), [
            "role_id" => "required",
            "permissions" => "required",                      
        ]);
        
        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);
        }        

        $role = $this->GetById(Role::class, $request->role_id);
        $role->syncPermissions([]);

        foreach ($request->permissions as $p) {
            $permission = Permission::findByName($p);
            $role->givePermissionTo($permission);
        }
               
        $data = [$role, $role->permissions->pluck('name')];
        return ResponseHelper::create_response($data, $role . " " ."now have permission to" . " ". $request->permission, 200);        
    }

    // public function UnassignPermission(Request $request){
    //     $validate = Validator::make($request->all(), [
    //         "role_id" => "required", 
    //         "permission" => "required",
    //         "guard_name" => "nullable"                       
    //     ]);
        
    //     if($validate->fails()){
    //         return response()->json([
    //            'success' => false,
    //             'message' =>  $validate->messages(),
    //             'data' => []
    //         ], 401);
    //     }

    //     $role = Role::find($request->role_id);
    //     $permission = Permission::findByName($request->permission, $request->guard_name);
    //     $role->revokePermissionTo($permission);

    //     return ResponseHelper::create_response($role->permissions, "Permission" . "" . $permission->name . "" . "is removed", 200);
    // }

    public function GetAllRolePermissions(){
        $roles = Role::with(['permissions'])->get();
        $data = $roles->map(function ($role) {
            return [
                'id' => $role->id,
                'role' => $role->name,                
                'permissions' => $role->permissions->pluck('name'),
            ];
        });

        return ResponseHelper::create_response($data, "List of all roles with permissions", 200);
    }

    public function GetRolePermissions(Request $request){
        $validate = Validator::make($request->all(), [
            "role_id" => "required",                        
        ]);
        
        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);
        }

        $role = GetRoleById($request->role_id);
        $data = $role->permissions->pluck('name');

        return ResponseHelper::create_response($data, "List of", 200);

    }
}
