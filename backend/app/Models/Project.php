<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'created_by',
    ];

    

    /**
     * Relationship to the User who created the project.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with Tasks (One-to-Many).
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Relationship with Subtasks through Tasks (Has Many Through).
     */
    public function subtasks()
    {
        return $this->hasManyThrough(SubTask::class, Task::class);
    }

    /**
     * Relationship with Comments through Tasks (Has Many Through).
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship with Teams through project_teams table (Many-to-Many).
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'project_teams')
            ->withTimestamps()
            ->withPivot('created_at');
    }

    /**
     * Relationship with Users through project_users table (Many-to-Many).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users')
            ->withTimestamps()
            ->withPivot('role_in_project');
    }

    
}
