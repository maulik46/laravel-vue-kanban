<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskValidator
{
    public function validateCreate($request)
    {
        $data = $request instanceof Request ? $request->all() : $request;
        
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'task_type_id' => 'required|exists:task_types,id',
            'color_code' => 'nullable|string|max:7',
        ])->validate();
    }
    
    public function validateUpdate($request)
    {
        $data = $request instanceof Request ? $request->all() : $request;
        
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'task_type_id' => 'required|exists:task_types,id',
            'color_code' => 'nullable|string|max:7',
        ])->validate();
    }
    
    public function validateReorder($request)
    {
        $data = $request instanceof Request ? $request->all() : $request;
        
        return Validator::make($data, [
            'task_id' => 'required|exists:tasks,id',
            'task_type_id' => 'required|exists:task_types,id',
            'index_order' => 'required|integer|min:0',
        ])->validate();
    }
}