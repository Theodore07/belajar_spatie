<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class UserController extends Controller
{
    
    
    public function GetUser(){       
        $data = $this->GetAllData(User::class);
        $response = ResponseHelper::create_response($data, "User Retrieved", 200);
        return $response;
    }

    public function GetUserById(Request $request){
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
            $data = $this->GetById(User::class, $request->id);
            if(!$data){
                return ResponseHelper::create_response($data, "User not found", 404);
            }else{
                return ResponseHelper::create_response($data, "User Found", 200);
            }
            
        }               
    }

    public function CreateUser(Request $request){
        $validate = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "password" => "required"
        ]);
        
        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);
        }else{
            $data = $this->CreateData($request, User::class);
            return ResponseHelper::create_response($data, "User created successfully", 201);
        }
    }

    public function EditUser(Request $request){
        $validate = Validator::make($request->all(), [
            "id" => "required",
            "name" => "nullable",
            "email" => "nullable|email",
            "password" => "nullable"
        ]);
        
        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);

        }else{
            $data = $this->EditData($request, User::class);
            return ResponseHelper::create_response($data, "User updated successfully", 200);
        }
    }

    public function DeleteUser(Request $request){
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
            return $this->DeleteData(User::class, $request->id);;
        }               
    }

    //return role and permission of the user
    public function GetUserRole(Request $request){
        $validate = Validator::make($request->all(), [
            "id" => "required"
        ]);

        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);
        }

        $user = User::find($request->id);

        if(!$user){
            return response()->json(["Message" => "User not found"], 401);
        }

        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');
        $data = ['roles' => $roles, 'permissions' => $permissions];
        return ResponseHelper::create_response($data, "The User's role retrieved", 200);
    }

    // public function UnassignedRole(Request $request){
    //     $validate = Validator::make($request->all(), [
    //         "id" => "required",
    //         "role_name" => "required",
    //         "guard_name" => "nullable",
    //     ]);

    //     if($validate->fails()){
    //         return response()->json([
    //            'success' => false,
    //             'message' =>  $validate->messages(),
    //             'data' => []
    //         ], 401);
    //     }

    //     $user = User::find($request->id);        
    //     $role = Role::findByName($request->role_name, $request->guard_name);
    //     $user->removeRole($role);
    //     $data = $user->getRoleNames();
    //     return ResponseHelper::create_response($data, "Role Removed", 200);
    // }

    //assigning role to user
    public function AssignRole(Request $request){
        $validate = Validator::make($request->json()->all(), [
            "id" => "required",
            "role_names" => "required",            
        ]);

        if($validate->fails()){
            return response()->json([
               'success' => false,
                'message' =>  $validate->messages(),
                'data' => []
            ], 401);
        }else{
           
            
            $user = User::find($request->id);
            if(!$user){
                return response()->json(["User not found", 404]);
            }
           
            $user->syncRoles([]);

            foreach ($request->role_names as $role_name) {
                $role = Role::where('name', $role_name)->first();                 
                $user->assignRole($role);
            }

            $data = ['user' => $user->name, 'roles' => $user->getRoleNames()];

            return ResponseHelper::create_response($data, "Assigned role to " . "" . $user->name, 200);
        }
    }

    public function GetUsersWithRoles(){
        $users = User::with(['roles', 'permissions'])->get();
        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'roles' => $user->roles->pluck('name'), // Get only the role names
                'permissions' => $user->permissions->pluck('name'), // Get only the permission names
            ];
        });

        return ResponseHelper::create_response($data, "List of Users with Roles and Permissions", 200);
    }

}
