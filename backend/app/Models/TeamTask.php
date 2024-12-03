<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamTask extends Model
{
    use HasFactory;

    protected $table = 'team_tasks';

    protected $fillable = [
        'team_id',
        'task_id',
        
    ];

    /**
     * Relationship to the Team model.
     * A TeamTask belongs to a Team.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relationship to the Task model.
     * A TeamTask belongs to a Task.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
