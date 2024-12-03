<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class ProjectManager extends User
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
                    $this->role = 'project_manager'; // Ensure that every Project Manager has the 'Project Manager' role
                    return parent::save($options);
          }

          /**
           * Get the projects managed by this Project Manager.
           */
          public function projects()
          {
                    return $this->belongsToMany(Project::class, 'project_users')
                              ->withPivot('role_in_project', 'Manager');
          }

}
