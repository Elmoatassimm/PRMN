<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProjectUser;
use App\Models\UserTeam;
use Illuminate\Support\Facades\Hash;

class StoreUserService
{
          /**
           * Store an admin user.
           *
           * @param array $userData
           * @return User
           */
          public function storeAdmin(array $userData): User
          {
                    return $this->createUser($userData, 'admin');
          }

          /**
           * Store a project manager user and associate with a project.
           *
           * @param array $userData
           * @return User
           */
          public function storeProjectManager(array $userData): User
          {
                    $user = $this->createUser($userData, 'project_manager');

                    if (isset($userData['invitable_id'])) {
                              $this->associateProject($userData['invitable_id'], $user);
                    }

                    return $user;
          }

          /**
           * Store a team member user and associate with a team.
           *
           * @param array $userData
           * @return User
           */
          public function storeTeamMember(array $userData): User
          {
                    $user = $this->createUser($userData, 'team_member');

                    if (isset($userData['invitable_id'])) {
                              $this->associateTeam($userData['invitable_id'], $user);
                    }

                    return $user;
          }

          /**
           * General method to create a user with a specific role.
           *
           * @param array $userData
           * @param string $role
           * @return User
           */
          private function createUser(array $userData, string $role): User
          {
                    // Create a new User instance or find an existing one by email
                    $user = User::firstOrNew(['email' => $userData['email']]);

                    // Fill user data and save
                    $user->fill([
                              'name' => $userData['name'],
                              'email' => $userData['email'],
                              'password' => bcrypt($userData['password']),
                              'role' => $role,
                    ]);
                    $user->save();

                    return $user;
          }

          /**
           * Attach a team to the user if they are a TeamMember.
           *
           * @param int $teamId
           * @param User $user
           */
          private function associateTeam(int $teamId, User $user)
          {
                    UserTeam::firstOrCreate([
                              'team_id' => $teamId,
                              'user_id' => $user->id,
                              'role' => 'member',
                    ]);
          }

          /**
           * Attach a project to the user if they are a ProjectManager.
           *
           * @param int $projectId
           * @param User $user
           */
          private function associateProject(int $projectId, User $user)
          {
                    ProjectUser::firstOrCreate([
                              'project_id' => $projectId,
                              'user_id' => $user->id,
                              'role_in_project' => $user->role,
                    ]);
          }
}
