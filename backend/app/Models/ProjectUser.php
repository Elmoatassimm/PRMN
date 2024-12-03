<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    use HasFactory;

    protected $table = 'project_users'; // Specifies the table name if it's not the default 'project_users'

    protected $fillable = [
        'project_id',
        'user_id',
        'role_in_project',
        
    ];

    /**
     * Relationship to the Project model.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
