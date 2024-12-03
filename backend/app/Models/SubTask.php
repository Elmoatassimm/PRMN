<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;

    protected $table = 'sub_tasks'; // Specifies the table name if it's not the default 'subtasks'

    protected $fillable = [
        'task_id',
        'title',
        'is_completed',
        
    ];

    /**
     * Relationship to the Task model.
     * Each SubTask belongs to one Task.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
