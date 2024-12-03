import axiosInstance from '../../axios';

const ProjectService = {
    createProject: async (projectData) => {
        const response = await axiosInstance.post('projects', projectData);
        return response;
    },
    
    getAllProjects: async () => {
        const response = await axiosInstance.get('projects');
        console.log(response);
        return response;
    },
    
    getProjectById: async (id) => {
        const response = await axiosInstance.get(`projects/${id}`);
        return response;
    },

    getProjectTasks: async (projectId) => {
          const response = await axiosInstance.get('projects');
          console.log(response);
          return response;
    },

    createTask: async (projectId, taskData) => {
        const response = await axiosInstance.post(`tasks`, taskData);
        return response;
    },

    updateTask: async (taskId, taskData) => {
        const response = await axiosInstance.put(`tasks/${taskId}`, taskData);
        return response;
    },

    deleteTask: async (taskId) => {
        const response = await axiosInstance.delete(`tasks/${taskId}`);
        return response;
    }
};

export default ProjectService;
