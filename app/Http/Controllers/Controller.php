<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\ResultResponse;

abstract class Controller
{
    //Abstract Class for implementing CRUD basic

    protected function GetAllData($model){       
        $data = $model::all();       
        return $data;
    }

    protected function GetById($model, $id){          
        $data = $model::find($id);          
        return $data;
    }

    protected function CreateData(Request $request, $model){
        $data = $model::create($request->all());
        return $data;        
    }

    protected function EditData(Request $request, $model){
        $item = $model::find($request->id);

        if($item == null){
            return response()->json(['message' => "Data not found", 'status' => 404], 404);
        }

        foreach ($request->all() as $key => $value) {
            if($item->$key !== $value && $value !== null){
                $item[$key] = $value;   
            }         
        }

        $item->save();
        return $item;
    }

    protected function DeleteData($model, $id){
        $data = $model::find((int)$id);

        if($data == null){
            return response()->json(['message' => "Data not found", 'status' => 404], 404);
        }

        $data->delete();
        return response()->json([$data, "Data is successfully deleted"], 200);
    }
}
