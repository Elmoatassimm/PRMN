import React, { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { fetchProjects } from '../redux/actions/projectActions';
import TaskComponent from '../components/TaskComponent';
import SidebarMenu from '../components/SidebarMenu';
import TeamsComponent from '../components/TeamsComponent';
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Badge } from "@/components/ui/badge";
import { CalendarIcon, UsersIcon, CheckCircleIcon } from "@heroicons/react/24/outline";

const Dashboard = () => {
  const dispatch = useDispatch();
  const { projects, loading, error } = useSelector((state) => state.project);
  const [activeSection, setActiveSection] = useState('projects');

  useEffect(() => {
    dispatch(fetchProjects());
  }, [dispatch]);

  if (loading) return <div className="flex h-screen items-center justify-center">Loading...</div>;
  if (error) return <div className="flex h-screen items-center justify-center text-destructive">Error: {error}</div>;

  const renderContent = () => {
    switch (activeSection) {
      case 'projects':
        return (
          <div className="grid grid-cols-1 gap-6">
            {projects && projects.map((project) => (
              <Card key={project.id} className="hover:shadow-lg transition-shadow duration-300">
                <CardHeader className="border-b bg-muted/30">
                  <CardTitle className="flex items-center justify-between">
                    <span>{JSON.parse(project.name).en}</span>
                    <Badge variant="secondary">{JSON.parse(project.status).en}</Badge>
                  </CardTitle>
                </CardHeader>
                <CardContent className="pt-6">
                  <p className="text-muted-foreground mb-6 leading-relaxed">{JSON.parse(project.description).en}</p>
                  
                  <div className="grid grid-cols-2 gap-6 mb-6">
                    <div className="flex items-center space-x-3">
                      <CalendarIcon className="h-5 w-5 text-muted-foreground" />
                      <div>
                        <p className="font-semibold text-sm">Project Timeline</p>
                        <p className="text-sm text-muted-foreground">{project.start_date} - {project.end_date}</p>
                      </div>
                    </div>
                    <div className="flex items-center space-x-3">
                      <CheckCircleIcon className="h-5 w-5 text-muted-foreground" />
                      <div>
                        <p className="font-semibold text-sm">Project Status</p>
                        <p className="text-sm text-muted-foreground">{JSON.parse(project.status).en}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div className="space-y-6">
                    <div className="bg-muted/30 p-4 rounded-lg">
                      <div className="flex items-center space-x-2 mb-4">
                        <UsersIcon className="h-5 w-5 text-muted-foreground" />
                        <p className="font-semibold">Teams ({project.teams.length})</p>
                      </div>
                      <div className="grid grid-cols-2 gap-2">
                        {project.teams.map(team => (
                          <Badge key={team.id} variant="outline" className="justify-center">
                            {team.name}
                          </Badge>
                        ))}
                      </div>
                    </div>

                    <div className="bg-muted/30 p-4 rounded-lg">
                      <p className="font-semibold mb-4">Team Members ({project.users.length})</p>
                      <div className="space-y-2">
                        {project.users.map(user => (
                          <div key={user.id} className="flex justify-between items-center bg-background p-2 rounded">
                            <span className="font-medium">{user.name}</span>
                            <Badge variant="secondary">{user.pivot.role_in_project}</Badge>
                          </div>
                        ))}
                      </div>
                    </div>

                    <div className="bg-muted/30 p-4 rounded-lg">
                      <p className="font-semibold mb-4">Tasks ({project.tasks.length})</p>
                      <TaskComponent projectId={project.id} />
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        );
      case 'teams':
        return <TeamsComponent projectId={projects[0]?.id}/>;
      case 'tasks':
        return (
          <Card>
            <CardContent className="p-6">
              <TaskComponent projectId={projects[0]?.id} />
            </CardContent>
          </Card>
        );
      default:
        return null;
    }
  };

  return (
    <div className="flex h-screen bg-background">
      <SidebarMenu 
        activeSection={activeSection} 
        setActiveSection={setActiveSection} 
      />

      <ScrollArea className="flex-1 p-8">
        <div className="max-w-7xl mx-auto">
          <h2 className="text-3xl font-bold mb-8 text-primary">
            {activeSection.charAt(0).toUpperCase() + activeSection.slice(1)}
          </h2>
          {renderContent()}
        </div>
      </ScrollArea>
    </div>
  );
};

export default Dashboard;
