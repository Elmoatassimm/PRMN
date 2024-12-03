<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'content',
    ];

    /**
     * Relationship to the project this comment belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship to the User who created this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
