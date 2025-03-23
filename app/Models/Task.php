<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "title",
        "description",
        "index_order",
        "task_type_id",
        "color_code",
    ];
    
    public function taskType()
    {
        return $this->belongsTo(TaskType::class);
    }
}
