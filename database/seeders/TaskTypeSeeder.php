<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskType::truncate();
        $taskTypes = [
            ['name' => 'To Do', 'slug' => 'todo'],
            ['name' => 'In Progress', 'slug' => 'in-progress'],
            ['name' => 'Done', 'slug' => 'done'],
        ];
        foreach($taskTypes as $taskType){
            TaskType::create($taskType);
        }
    }
}
