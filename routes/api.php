<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/login',[AuthController::class, 'APILogin']);

Route::middleware(['auth:api'], 'permission:view content|access content (api),api')->group(function(){
    Route::prefix('user')->group(function(){
        Route::get('/GetUser', [UserController::class, "GetUser"]);
        Route::post('/FindUser', [UserController::class, "GetUserById"]);
        Route::get('/GetUserRole', [UserController::class, "GetUserRole"]);

        // Yang bisa dipake disini cuma yang guardnya API
        Route::middleware(['permission:customize user data|edit user (api)'])->group(function () {
            Route::post('/CreateUser', [UserController::class, "CreateUser"]);
            Route::post('/UpdateUser', [UserController::class, "EditUser"]);
            Route::post('/DeleteUser', [UserController::class, "DeleteUser"]);            
            Route::get('/GetUserWithRP', [UserController::class, "GetUsersWithRoles"]);
            Route::post('/AssignUser', [UserController::class, "AssignRole"]);
            Route::post('/UnassignUser', [UserController::class, "UnassignedRole"]);
        });               
    });

    // Yang bisa dipake disini cuma yang guardnya API
    Route::prefix('role')->group(function(){
        Route::middleware(['auth:api','role_or_permission:customize role data|edit role (api)'])->group(function () {
            Route::get('/GetRoles', [RoleController::class, "GetRoles"]);
            Route::get('/GetRolesPermissions', [RoleController::class, "GetAllRolePermissions"]);
            Route::post('/CreateRole', [RoleController::class, "CreateRoles"]);
            Route::post('/FindRole', [RoleController::class, "GetRoleById"]);
            Route::post('/UpdateRole', [RoleController::class, "EditRole"]);
            Route::post('/DeleteRole', [RoleController::class, "DeleteRoles"]);
            Route::post('/AssignPermission',[RoleController::class, "AssignPermission"]);
            Route::post('/UnassignPermission',[RoleController::class, "UnassignPermission"]);
        });
    });

    Route::prefix('permission')->group(function(){
        Route::middleware(['permission:customize permission data|edit permission (api)'])->group(function () {
            Route::get('/GetPermissions', [PermissionController::class, "GetPermissions"]);
            Route::post('/CreatePermission', [PermissionController::class, "CreatePermission"]);
            Route::post('/FindPermission', [PermissionController::class, "GetPermissionById"]);
            Route::post('/UpdatePermission', [PermissionController::class, "EditPermission"]);
            Route::post('/DeletePermission', [PermissionController::class, "DeletePermission"]);
        });        
    });

    Route::post('/logout', [AuthController::class, "APILogout"]);

});