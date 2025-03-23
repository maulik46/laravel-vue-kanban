<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{

    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('index_order');
    }
}
