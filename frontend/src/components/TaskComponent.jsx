import React, { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { fetchProjects, updateTask, deleteTask, createTask } from '../redux/actions/projectActions';
import { format } from 'date-fns';
import axiosInstance from '../axios';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Textarea } from "@/components/ui/textarea";

const TaskComponent = ({ projectId }) => {
  const dispatch = useDispatch();
  const [isEditing, setIsEditing] = useState(false);
  const [editedTask, setEditedTask] = useState(null);
  const [editedSubtask, setEditedSubtask] = useState(null);
  const [isAddingTask, setIsAddingTask] = useState(false);
  const [selectedTeam, setSelectedTeam] = useState('');
  const [filters, setFilters] = useState({
    status: '',
    priority: '',
    search: ''
  });
  const [newTask, setNewTask] = useState({
    title: '',
    description: '',
    due_date: '',
    priority: 'medium',
    status: 'pending',
    project_id: projectId,
    team_id: ''
  });

  const projects = useSelector(state => state.project.projects);
  const project = projects.find(p => p.id === projectId);
  const allTasks = project?.tasks || [];
  const teams = useSelector((state) => state.project.projects[0]?.teams);

  // Filter tasks based on selected filters
  const tasks = allTasks.filter(task => {
    const matchesStatus = !filters.status || task.status === filters.status;
    const matchesPriority = !filters.priority || task.priority === filters.priority;
    const matchesSearch = !filters.search || 
      task.title.toLowerCase().includes(filters.search.toLowerCase()) ||
      task.description.toLowerCase().includes(filters.search.toLowerCase());
    return matchesStatus && matchesPriority && matchesSearch;
  });

  const handleFilterChange = (e) => {
    const { name, value } = e.target;
    setFilters(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleTeamSelect = async (taskId, teamId) => {
    try {
      const response = await axiosInstance.post('taskteams', {
        task_id: taskId,
        team_id: teamId
      });

      if (response.status === 201) {
        await dispatch(fetchProjects());
      }
    } catch (error) {
      console.error('Error assigning team:', error);
    }
  };

  const handleAddTask = async (e) => {
    e.preventDefault();
    try {
      if (!newTask.title || !newTask.description || !newTask.due_date) {
        return;
      }
  
      const createdTask = await dispatch(createTask(projectId, newTask));
  
      if (newTask.team_id && createdTask?.id) {
        await axiosInstance.post('taskteams', {
          task_id: createdTask.id,
          team_id: newTask.team_id
        });
      }
  
      await dispatch(fetchProjects());
  
      setNewTask({
        title: '',
        description: '',
        due_date: '',
        priority: 'medium',
        status: 'pending',
        project_id: projectId,
        team_id: ''
      });
      setIsAddingTask(false);
    } catch (error) {
      console.error('Error adding task:', error);
    }
  };

  const handleEditTask = (task) => {
    setEditedTask({
      ...task,
      due_date: task.due_date.split('T')[0],
    });
    setIsEditing(true);
  };

  const handleEditSubtask = (taskId, subtask) => {
    setEditedSubtask({
      taskId,
      ...subtask
    });
    setIsEditing(true);
  };

  const handleSave = async () => {
    try {
      if (editedTask) {
        if (!editedTask.title || !editedTask.description || !editedTask.due_date) {
          return;
        }
        await dispatch(updateTask(editedTask.id, editedTask));
        setIsEditing(false);
        setEditedTask(null);
      } else if (editedSubtask) {
        const updatedTask = tasks.find(t => t.id === editedSubtask.taskId);
        const updatedSubtasks = updatedTask.subtasks.map(subtask =>
          subtask.id === editedSubtask.id ? editedSubtask : subtask
        );
        updatedTask.subtasks = updatedSubtasks;

        await dispatch(updateTask(updatedTask.id, updatedTask));
        setIsEditing(false);
        setEditedSubtask(null);
      }
    } catch (error) {
      console.error('Error updating task:', error);
    }
  };

  const handleDelete = async (taskId) => {
    try {
      await dispatch(deleteTask(taskId));
      await dispatch(fetchProjects());
    } catch (error) {
      console.error('Error deleting task:', error);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (editedTask) {
      setEditedTask(prev => ({
        ...prev,
        [name]: value
      }));
    } else if (editedSubtask) {
      setEditedSubtask(prev => ({
        ...prev,
        [name]: value
      }));
    } else if (isAddingTask) {
      setNewTask(prev => ({
        ...prev,
        [name]: value
      }));
    }
  };

  if (isAddingTask) {
    return (
      <Card>
        <CardHeader>
          <CardTitle>Add New Task</CardTitle>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleAddTask} className="space-y-4">
            <div>
              <Label htmlFor="title">Title</Label>
              <Input
                id="title"
                name="title" 
                value={newTask.title}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <Label htmlFor="description">Description</Label>
              <Textarea
                id="description"
                name="description"
                value={newTask.description}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <Label htmlFor="due_date">Due Date</Label>
              <Input
                id="due_date"
                type="date"
                name="due_date"
                value={newTask.due_date}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <Label htmlFor="priority">Priority</Label>
              <Select 
                name="priority"
                value={newTask.priority}
                onValueChange={(value) => handleChange({target: {name: 'priority', value}})}>
                <SelectTrigger>
                  <SelectValue placeholder="Select priority" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="low">Low</SelectItem>
                  <SelectItem value="medium">Medium</SelectItem>
                  <SelectItem value="high">High</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="flex gap-2">
              <Button type="submit">Add Task</Button>
              <Button variant="outline" onClick={() => setIsAddingTask(false)}>
                Cancel
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    );
  }

  if (isEditing && (editedTask || editedSubtask)) {
    return (
      <Card>
        <CardHeader>
          <CardTitle>{editedTask ? 'Edit Task' : 'Edit Subtask'}</CardTitle>
        </CardHeader>
        <CardContent>
          {editedTask && (
            <div className="space-y-4">
              <div>
                <Label htmlFor="title">Title</Label>
                <Input
                  id="title"
                  name="title"
                  value={editedTask.title}
                  onChange={handleChange}
                  required
                />
              </div>

              <div>
                <Label htmlFor="description">Description</Label>
                <Textarea
                  id="description"
                  name="description"
                  value={editedTask.description}
                  onChange={handleChange}
                  required
                />
              </div>

              <div>
                <Label htmlFor="due_date">Due Date</Label>
                <Input
                  id="due_date"
                  type="date"
                  name="due_date"
                  value={editedTask.due_date}
                  onChange={handleChange}
                  required
                />
              </div>
            </div>
          )}

          {editedSubtask && (
            <div className="space-y-4">
              <div>
                <Label htmlFor="title">Subtask Title</Label>
                <Input
                  id="title"
                  name="title"
                  value={editedSubtask.title}
                  onChange={handleChange}
                  required
                />
              </div>

              <div>
                <Label htmlFor="status">Status</Label>
                <Select
                  name="status"
                  value={editedSubtask.status}
                  onValueChange={(value) => handleChange({target: {name: 'status', value}})}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select status" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="pending">Pending</SelectItem>
                    <SelectItem value="in_progress">In Progress</SelectItem>
                    <SelectItem value="completed">Completed</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
          )}

          <div className="flex gap-2 mt-4">
            <Button onClick={handleSave}>Save</Button>
            <Button variant="outline" onClick={() => {
              setIsEditing(false);
              setEditedTask(null);
              setEditedSubtask(null);
            }}>
              Cancel
            </Button>
          </div>
        </CardContent>
      </Card>
    );
  }

  return (
    <div className="space-y-4">
      <div className="flex justify-between items-center">
        <Button onClick={() => setIsAddingTask(true)}>
          Add New Task
        </Button>

        <div className="flex gap-4">
          <Input
            name="search"
            value={filters.search}
            onChange={handleFilterChange}
            placeholder="Search tasks..."
          />
          
          <Select 
            name="status"
            value={filters.status}
            onValueChange={(value) => handleFilterChange({target: {name: 'status', value}})}>
            <SelectTrigger>
              <SelectValue placeholder="All Status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">All Status</SelectItem>
              <SelectItem value="pending">Pending</SelectItem>
              <SelectItem value="in_progress">In Progress</SelectItem>
              <SelectItem value="completed">Completed</SelectItem>
            </SelectContent>
          </Select>

          <Select
            name="priority"
            value={filters.priority}
            onValueChange={(value) => handleFilterChange({target: {name: 'priority', value}})}>
            <SelectTrigger>
              <SelectValue placeholder="All Priority" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">All Priority</SelectItem>
              <SelectItem value="low">Low</SelectItem>
              <SelectItem value="medium">Medium</SelectItem>
              <SelectItem value="high">High</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Title</TableHead>
            <TableHead>Description</TableHead>
            <TableHead>Priority</TableHead>
            <TableHead>Status</TableHead>
            <TableHead>Due Date</TableHead>
            <TableHead>Assigned Team</TableHead>
            <TableHead>Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {tasks.map(task => (
            <TableRow key={task.id}>
              <TableCell>{task.title}</TableCell>
              <TableCell>{task.description}</TableCell>
              <TableCell>
                <Badge variant={
                  task.priority === 'high' ? 'destructive' :
                  task.priority === 'medium' ? 'default' :
                  'secondary'
                }>
                  {task.priority?.charAt(0).toUpperCase() + task.priority?.slice(1)}
                </Badge>
              </TableCell>
              <TableCell>
                <Badge variant={
                  task.status === 'completed' ? 'default' :
                  task.status === 'in_progress' ? 'secondary' :
                  'outline'
                }>
                  {task.status?.replace('_', ' ').charAt(0).toUpperCase() + task.status?.replace('_', ' ').slice(1)}
                </Badge>
              </TableCell>
              <TableCell>{format(new Date(task.due_date), 'PPP')}</TableCell>
              <TableCell>
                <Select
                  value={selectedTeam}
                  onValueChange={(value) => handleTeamSelect(task.id, value)}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select Team" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="unassigned">Select Team</SelectItem>
                    {teams.map(team => (
                      <SelectItem key={team.id} value={team.id}>{team.name}</SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </TableCell>
              <TableCell>
                <div className="flex gap-2">
                  <Button variant="outline" onClick={() => handleEditTask(task)}>
                    Edit
                  </Button>
                  <Button variant="destructive" onClick={() => handleDelete(task.id)}>
                    Delete
                  </Button>
                </div>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  );
};

export default TaskComponent;