<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Admin extends User implements JWTSubject
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


          /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
          'password',
          'remember_token',
      ];
  
      /**
       * Get the attributes that should be cast.
       *
       * @return array<string, string>
       */
      protected function casts(): array
      {
          return [
              'email_verified_at' => 'datetime',
              'password' => 'hashed',
          ];
      }
  
      /**
       * Get the identifier that will be stored in the subject claim of the JWT.
       *
       * @return mixed
       */
      public function getJWTIdentifier()
      {
          return $this->getKey();
      }
  
      /**
       * Return a key value array, containing any custom claims to be added to the JWT.
       *
       * @return array
       */
      public function getJWTCustomClaims()
      {
          return [];
      }







          public function save(array $options = [])
          {
                    $this->role = 'admin';
                    return parent::save($options);
          }

          /**
           * Get all projects managed by this admin.
           */
          public function projects()
          {
                    return $this->hasMany(Project::class, 'created_by');
          }

          /**
           * Get notifications for this admin.
           */
          

          public function adminProjects()
          {
                    return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id')
                              ->wherePivot('role_in_project', 'Admin');
          }
}
