<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;


    protected $table = 'tasks';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    /**
     * Relationship to the Project 
     * Each Task belongs to one Project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship to the SubTask model.
     * A Task can have multiple SubTasks.
     */
    public function subtasks()
    {
        return $this->hasMany(SubTask::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_tasks')
                    ->withTimestamps(); // If you want to keep track of when teams are assigned to tasks
    }

    
}
