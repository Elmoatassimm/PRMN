<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    protected $fillable = [
        'name',
        'created_by',
    ];

    /**
     * Relationship to the User model.
     * A Team can have multiple Users through the user_team pivot table.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_team')
                    ->withTimestamps(); // If you want to track when users joined the team
    }

    /**
     * Relationship to the Project model.
     * A Team can be associated with multiple Projects through the project_teams pivot table.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_teams')
                    ->withTimestamps(); // If you want to track when teams are assigned to projects
    }

    /**
     * Relationship to the Task model.
     * A Team can be assigned to multiple Tasks through the team_tasks pivot table.
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'team_tasks')
                    ->withTimestamps(); // If you want to track when teams are assigned to tasks
    }

    /**
     * Relationship to the User model for the creator.
     * A Team is created by a User.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}