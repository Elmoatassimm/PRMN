<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeam extends Model
{
    use HasFactory;

    protected $table = 'user_teams';

    protected $fillable = [
        'user_id',
        'team_id',
        'role',
    ];

    /**
     * Relationship to the User model.
     * A UserTeam belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to the Team model.
     * A UserTeam belongs to a Team.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
