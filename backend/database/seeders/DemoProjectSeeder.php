<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\ProjectUser;
use App\Models\UserTeam;
use App\Models\ProjectTeam;
use App\Models\TeamTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoProjectSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create 2 project managers
        $projectManagers = User::factory()->count(2)->create([
            'role' => 'project_manager',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create 10 team members
        $teamMembers = User::factory()->count(10)->create([
            'role' => 'team_member',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create a demo project
        $project = Project::factory()->create([
            'name' => 'Demo Project',
            'description' => 'A demo project with admin, project managers, and team members',
            'created_by' => $admin->id,
            'status' => 'in_progress',
        ]);

        // Attach admin to project
        ProjectUser::create([
            'project_id' => $project->id,
            'user_id' => $admin->id,
            'role_in_project' => 'admin'
        ]);

        // Attach project managers to project
        foreach ($projectManagers as $manager) {
            ProjectUser::create([
                'project_id' => $project->id,
                'user_id' => $manager->id,
                'role_in_project' => 'project_manager'
            ]);
        }

        // Attach team members to project
        foreach ($teamMembers as $member) {
            ProjectUser::create([
                'project_id' => $project->id,
                'user_id' => $member->id,
                'role_in_project' => 'team_member'
            ]);
        }

        // Create 3 teams: Development, Design, and QA
        $teams = [
            'Development Team' => array_slice($teamMembers->toArray(), 0, 4),
            'Design Team' => array_slice($teamMembers->toArray(), 4, 3),
            'QA Team' => array_slice($teamMembers->toArray(), 7, 3),
        ];

        foreach ($teams as $teamName => $members) {
            $team = Team::create([
                'name' => $teamName,
                'created_by' => $admin->id,
            ]);

            // Attach team to project
            ProjectTeam::create([
                'project_id' => $project->id,
                'team_id' => $team->id,
            ]);

            // Assign project manager to team
            UserTeam::create([
                'user_id' => $projectManagers[0]->id,
                'team_id' => $team->id,
                
            ]);

            // Attach team members
            foreach ($members as $member) {
                UserTeam::create([
                    'user_id' => $member['id'],
                    'team_id' => $team->id,
                    
                ]);
            }

            // Create tasks for each team
            $tasks = $this->getTasksForTeam($teamName, $project->id);
            foreach ($tasks as $taskData) {
                $task = Task::create($taskData);

                // Assign task to team
                TeamTask::create([
                    'team_id' => $team->id,
                    'task_id' => $task->id,
                ]);

                // Create subtasks
                foreach (range(1, 3) as $i) {
                    SubTask::create([
                        'task_id' => $task->id,
                        'title' => "Subtask {$i} for {$task->title}",
                        'is_completed' => false,
                    ]);
                }
            }
        }
    }

    private function getTasksForTeam($teamName, $projectId)
    {
        $tasks = [
            'Development Team' => [
                [
                    'title' => 'Setup Development Environment',
                    'description' => 'Configure development environment and tools',
                    'priority' => 'high',
                    'status' => 'completed',
                ],
                [
                    'title' => 'Implement User Authentication',
                    'description' => 'Develop user authentication system',
                    'priority' => 'high',
                    'status' => 'in_progress',
                ],
                [
                    'title' => 'Create API Endpoints',
                    'description' => 'Develop and document API endpoints',
                    'priority' => 'medium',
                    'status' => 'pending',
                ],
            ],
            'Design Team' => [
                [
                    'title' => 'Create UI/UX Design',
                    'description' => 'Design user interface and experience',
                    'priority' => 'high',
                    'status' => 'in_progress',
                ],
                [
                    'title' => 'Design System Components',
                    'description' => 'Create reusable design components',
                    'priority' => 'medium',
                    'status' => 'pending',
                ],
            ],
            'QA Team' => [
                [
                    'title' => 'Create Test Plan',
                    'description' => 'Develop comprehensive test plan',
                    'priority' => 'high',
                    'status' => 'completed',
                ],
                [
                    'title' => 'Perform Integration Testing',
                    'description' => 'Test integration between components',
                    'priority' => 'medium',
                    'status' => 'in_progress',
                ],
                [
                    'title' => 'User Acceptance Testing',
                    'description' => 'Conduct UAT with stakeholders',
                    'priority' => 'low',
                    'status' => 'pending',
                ],
            ],
        ];

        return array_map(function ($task) use ($projectId) {
            return array_merge($task, [
                'project_id' => $projectId,
                'due_date' => now()->addDays(rand(1, 30)),
            ]);
        }, $tasks[$teamName]);
    }
}
