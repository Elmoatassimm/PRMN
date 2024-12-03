<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class TeamMember extends User
{
          use HasFactory, Notifiable;
          protected $table = "users";
          protected $fillable = [
                    'name',
                    'email',
                    'password',
                    'google_id',
                    'avatar',
                    'email_verified_at',
                    'role',
          ];

          public function save(array $options = [])
          {
                    $this->role = 'team_member'; // Ensure that every Team Member has the 'Team Member' role
                    return parent::save($options);
          }

          public function projects()
          {
                    return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id')
                              ->wherePivot('role_in_project', 'Contributor');
          }

          /**
           * Get all tasks assigned to the team member through teams.
           *
           * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
           */
          public function tasks()
          {
                    return $this->belongsToMany(Task::class, 'team_tasks', 'team_id', 'task_id')
                              ->withTimestamps();
          }

          /**
           * Get all teams the team member belongs to.
           *
           * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
           */
          public function teams()
          {
                    return $this->belongsToMany(Team::class, 'user_teams', 'user_id', 'team_id')
                              ->withTimestamps();
          }
}
