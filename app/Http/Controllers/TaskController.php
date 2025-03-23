<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $taskTypes = TaskType::with('tasks')->get();
        
        return Inertia::render('Home', [
            'taskTypes' => $taskTypes
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_type_id' => 'required|exists:task_types,id',
            'color_code' => 'nullable|string|max:7',
        ]);
        
        $maxOrder = Task::where('task_type_id', $request->task_type_id)->max('index_order') ?? 0;
        
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'task_type_id' => $validated['task_type_id'],
            'color_code' => $validated['color_code'] ?? '#333333',
            'index_order' => $maxOrder + 1,
        ]);
        
        return redirect()->back()->with(['success' => 'Task created successfully.']);
    }
    
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_type_id' => 'required|exists:task_types,id',
            'color_code' => 'nullable|string|max:7',
        ]);
        
        $orderChange = $task->task_type_id != $validated['task_type_id'];
        
        if ($orderChange) {
            $maxOrder = Task::where('task_type_id', $validated['task_type_id'])->max('index_order') ?? 0;
            $task->index_order = $maxOrder + 1;
        }
        
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'task_type_id' => $validated['task_type_id'],
            'color_code' => $validated['color_code'] ?? '#333333',
        ]);
        
        return redirect()->back()->with(['success' => 'Task updated successfully.']);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'task_type_id' => 'required|exists:task_types,id',
            'index_order' => 'required|integer|min:0',
        ]);
        
        $task = Task::findOrFail($validated['task_id']);
        $oldTypeId = $task->task_type_id;
        $newTypeId = $validated['task_type_id'];
        $newPosition = $validated['index_order'];
        
        if ($oldTypeId == $newTypeId) {
            if ($task->index_order > $newPosition) {
                Task::where('task_type_id', $newTypeId)
                    ->where('index_order', '>=', $newPosition)
                    ->where('index_order', '<', $task->index_order)
                    ->increment('index_order');
            } else {
                Task::where('task_type_id', $newTypeId)
                    ->where('index_order', '>', $task->index_order)
                    ->where('index_order', '<=', $newPosition)
                    ->decrement('index_order');
            }
        } 
        else {
            Task::where('task_type_id', $oldTypeId)
                ->where('index_order', '>', $task->index_order)
                ->decrement('index_order');
                
            Task::where('task_type_id', $newTypeId)
                ->where('index_order', '>=', $newPosition)
                ->increment('index_order');
        }
        
        $task->update([
            'task_type_id' => $newTypeId,
            'index_order' => $newPosition
        ]);
        
        return redirect()->back()->with(['success' => 'Task moved successfully.']);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        
        return redirect()->back()->with(['success' => 'Task deleted successfully.']);
    }
}
