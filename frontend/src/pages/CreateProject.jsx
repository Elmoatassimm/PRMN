import React, { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { createProject } from '../redux/actions/projectActions';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "../components/ui/card";
import { Input } from "../components/ui/input";
import { Label } from "../components/ui/label";
import { Textarea } from "../components/ui/textarea";
import { Button } from "../components/ui/button";
import { useNavigate } from 'react-router-dom';

const CreateProject = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const loading = useSelector((state) => state.project.loading);
  const error = useSelector((state) => state.project.error);

  const [name, setName] = useState({ en: '', fr: '', ar: '' });
  const [description, setDescription] = useState({ en: '', fr: '', ar: '' });
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();

    const projectData = {
      name: JSON.stringify(name),
      description: JSON.stringify(description),
      start_date: startDate,
      end_date: endDate,
    };

    
      const response = await dispatch(createProject(projectData));
      console.log(response);
      console.log(response.data.project.id);
      if (response.data.project.id) {
        navigate(`/inviteProjectManager/${response.data.project.id}`);
      }
    
  };

  const handleChange = (e, field, lang) => {
    const setState = field === 'name' ? setName : setDescription;
    const value = field === 'name' ? name : description;
    setState({
      ...value,
      [lang]: e.target.value,
    });
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle>Create Project</CardTitle>
        <CardDescription>Create a new project</CardDescription>
      </CardHeader>
      <CardContent>
        {error && <div className="text-red-500">{error}</div>}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <Label htmlFor="name-en">Project Name (English):</Label>
            <Input
              type="text"
              id="name-en"
              value={name.en}
              onChange={(e) => handleChange(e, 'name', 'en')}
              required
            />
          </div>
          <div>
            <Label htmlFor="name-fr">Project Name (French):</Label>
            <Input
              type="text"
              id="name-fr"
              value={name.fr}
              onChange={(e) => handleChange(e, 'name', 'fr')}
              required
            />
          </div>
          <div>
            <Label htmlFor="name-ar">Project Name (Arabic):</Label>
            <Input
              type="text"
              id="name-ar"
              value={name.ar}
              onChange={(e) => handleChange(e, 'name', 'ar')}
              required
            />
          </div>

          <div>
            <Label htmlFor="description-en">Description (English):</Label>
            <Textarea
              id="description-en"
              value={description.en}
              onChange={(e) => handleChange(e, 'description', 'en')}
              required
            />
          </div>
          <div>
            <Label htmlFor="description-fr">Description (French):</Label>
            <Textarea
              id="description-fr"
              value={description.fr}
              onChange={(e) => handleChange(e, 'description', 'fr')}
              required
            />
          </div>
          <div>
            <Label htmlFor="description-ar">Description (Arabic):</Label>
            <Textarea
              id="description-ar"
              value={description.ar}
              onChange={(e) => handleChange(e, 'description', 'ar')}
              required
            />
          </div>

          <div>
            <Label htmlFor="start_date">Start Date:</Label>
            <Input
              type="date"
              id="start_date"
              value={startDate}
              onChange={(e) => setStartDate(e.target.value)}
              required
            />
          </div>
          <div>
            <Label htmlFor="end_date">End Date:</Label>
            <Input
              type="date"
              id="end_date"
              value={endDate}
              onChange={(e) => setEndDate(e.target.value)}
            />
          </div>
          <Button type="submit" disabled={loading} variant="primary">
            {loading ? 'Creating...' : 'Create Project'}
          </Button>
        </form>
      </CardContent>
      <CardFooter>
        <p>Card Footer</p>
      </CardFooter>
    </Card>
  );
};

export default CreateProject;