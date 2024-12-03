<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'expires_at',
        'user_email',
        'invitable_id',
        'invitable_type',
        'invited_by',
        'status',
    ];

    /**
     * Define the polymorphic relationship to projects or teams.
     */
    public function invitable()
    {
        return $this->morphTo();
    }

    /**
     * Relationship to the User who sent the invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Scope for filtering invitations by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    
}
