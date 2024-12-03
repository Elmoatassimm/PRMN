import React, { useState } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { fetchProjects } from '../redux/actions/projectActions';
import { createTeam, deleteTeam } from '../redux/actions/teamActions';
import InviteTeamMember from './inviteteammember';
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import { Trash2 } from "lucide-react";

const TeamsComponent = ({ projectId }) => {
  const { loading, error } = useSelector((state) => state.project);
  const teams = useSelector((state) => state.project.projects[0]?.teams);
  const dispatch = useDispatch();
  
  console.log(projectId);
  
  const [newTeam, setNewTeam] = useState({
    name: '',
    project_id: projectId
  });

  if (loading) return <div>Loading teams...</div>;
  if (error) return <div>Error loading teams: {error}</div>;

  const handleDeleteTeam = async (teamId) => {
    if (window.confirm('Are you sure you want to delete this team?')) {
      await dispatch(deleteTeam(teamId));
      dispatch(fetchProjects());
    }
  };

  const handleCreateTeam = async (e) => {
    e.preventDefault();
    if (!newTeam.name.trim()) {
      alert('Team name is required');
      return;
    }
    await dispatch(createTeam({ ...newTeam, project_id: projectId }));
    dispatch(fetchProjects());
    setNewTeam({ name: '', project_id: '' });
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setNewTeam(prev => ({
      ...prev,
      [name]: value
    }));
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <Card className="mb-8">
        <CardHeader>
          <h2 className="text-2xl font-bold">Create New Team</h2>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleCreateTeam}>
            <div className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="name">Team Name</Label>
                <Input
                  type="text"
                  id="name"
                  name="name"
                  value={newTeam.name}
                  onChange={handleInputChange}
                  required
                  placeholder="Enter team name"
                />
              </div>
              <Button type="submit">
                Create Team
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {teams && teams.length > 0 ? (
          teams.map((team) => (
            <Card key={team.id}>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 className="text-xl font-semibold">{team.name}</h3>
                <Button 
                  variant="ghost" 
                  size="icon"
                  onClick={() => handleDeleteTeam(team.id)}
                  className="text-destructive hover:text-destructive/90"
                >
                  <Trash2 className="h-5 w-5" />
                </Button>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div>
                    <h4 className="text-sm font-medium mb-2">Team Members</h4>
                    <div className="flex flex-wrap gap-2">
                      {team.members && team.members.length > 0 ? (
                        team.members.map((member) => (
                          <Badge 
                            key={member.id}
                            variant="secondary"
                          >
                            {member.name}
                          </Badge>
                        ))
                      ) : (
                        <p className="text-sm text-muted-foreground italic">No members yet</p>
                      )}
                    </div>
                  </div>

                  <div>
                    <InviteTeamMember teamId={team.id} />
                  </div>
                </div>
              </CardContent>
            </Card>
          ))
        ) : (
          <div className="col-span-full text-center text-muted-foreground">
            No teams found. Create your first team above!
          </div>
        )}
      </div>
    </div>
  );
};

export default TeamsComponent;