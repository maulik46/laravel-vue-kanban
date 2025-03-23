<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    protected $taskService;
    
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $taskTypes = $this->taskService->getAllTaskTypes();
        
        return Inertia::render('Home', [
            'taskTypes' => $taskTypes
        ]);
    }
    
    public function store(Request $request)
    {
        $this->taskService->createTask($request);
        
        return redirect()->back()->with(['success' => 'Task created successfully.']);
    }
  
    public function update(Request $request, Task $task)
    {
        $this->taskService->updateTask($task, $request);
        
        return redirect()->back()->with(['success' => 'Task updated successfully.']);
    }

   
    public function reorder(Request $request)
    {
        $this->taskService->reorderTask($request);
        
        return redirect()->back()->with(['success' => 'Task moved successfully.']);
    }

   
    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        
        return redirect()->back()->with(['success' => 'Task deleted successfully.']);
    }
}