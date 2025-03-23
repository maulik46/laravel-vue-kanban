<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskType;
use App\Validators\TaskValidator;

class TaskService
{
   
    protected $validator;
    
    public function __construct(TaskValidator $validator)
    {
        $this->validator = $validator;
    }
    
   
    public function getAllTaskTypes()
    {
        return TaskType::with('tasks')->get();
    }
  
    public function createTask($data)
    {
        $validated = $this->validator->validateCreate($data);
        
        $maxOrder = Task::where('task_type_id', $validated['task_type_id'])->max('index_order') ?? 0;
        
        return Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'task_type_id' => $validated['task_type_id'],
            'color_code' => $validated['color_code'] ?? '#333333',
            'index_order' => $maxOrder + 1,
        ]);
    }
    
  
    public function updateTask(Task $task, $data)
    {
        $validated = $this->validator->validateUpdate($data);
        
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
        
        return $task;
    }
   
    public function reorderTask($data)
    {
        $validated = $this->validator->validateReorder($data);
        
        $task = Task::findOrFail($validated['task_id']);
        $oldTypeId = $task->task_type_id;
        $newTypeId = $validated['task_type_id'];
        $newPosition = $validated['index_order'];
        
        if ($oldTypeId == $newTypeId) {
            $this->reorderTaskInSameType($task, $newPosition);
        } else {
            $this->reorderTaskBetweenTypes($task, $oldTypeId, $newTypeId, $newPosition);
        }
        
        $task->update([
            'task_type_id' => $newTypeId,
            'index_order' => $newPosition
        ]);
        
        return $task;
    }
    
   
    public function deleteTask(Task $task)
    {
        return $task->delete();
    }
    
    
    private function reorderTaskInSameType(Task $task, int $newPosition)
    {
        if ($task->index_order > $newPosition) {
            Task::where('task_type_id', $task->task_type_id)
                ->where('index_order', '>=', $newPosition)
                ->where('index_order', '<', $task->index_order)
                ->increment('index_order');
        } else {
            Task::where('task_type_id', $task->task_type_id)
                ->where('index_order', '>', $task->index_order)
                ->where('index_order', '<=', $newPosition)
                ->decrement('index_order');
        }
    }
    
    private function reorderTaskBetweenTypes(Task $task, int $oldTypeId, int $newTypeId, int $newPosition)
    {
        Task::where('task_type_id', $oldTypeId)
            ->where('index_order', '>', $task->index_order)
            ->decrement('index_order');
            
        Task::where('task_type_id', $newTypeId)
            ->where('index_order', '>=', $newPosition)
            ->increment('index_order');
    }
}