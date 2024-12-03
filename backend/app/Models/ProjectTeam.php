<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    use HasFactory;


    protected $table = 'project_teams'; // Specifies the table name if it's not the default 'project_teams'

    protected $fillable = [
        'project_id',
        'team_id',
    
    ];

    /**
     * Relationship to the Project model.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship to the Team model.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
