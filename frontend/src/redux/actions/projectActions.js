import ProjectService from '../services/ProjectService';

export const createProject = (projectData) => async (dispatch) => {
    dispatch({ type: 'CREATE_PROJECT_REQUEST' });
    try {
        const response = await ProjectService.createProject(projectData);
        dispatch({ type: 'CREATE_PROJECT_SUCCESS', payload: response.data });
        return response.data;
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message;
        dispatch({ type: 'CREATE_PROJECT_FAILURE', payload: errorMessage });
        throw error;
    }
};

export const fetchProjects = () => async (dispatch) => {
    dispatch({ type: 'FETCH_PROJECTS_REQUEST' });
    try {
        const response = await ProjectService.getAllProjects();
        dispatch({ type: 'FETCH_PROJECTS_SUCCESS', payload: response.data.data.data });
        return response.data.data.data;
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message;
        dispatch({ type: 'FETCH_PROJECTS_FAILURE', payload: errorMessage });
        throw error;
    }
};

export const fetchProjectById = (id) => async (dispatch) => {
    dispatch({ type: 'FETCH_PROJECT_REQUEST' });
    try {
        const response = await ProjectService.getProjectById(id);
        dispatch({ type: 'FETCH_PROJECT_SUCCESS', payload: response.data });
        return response.data;
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message;
        dispatch({ type: 'FETCH_PROJECT_FAILURE', payload: errorMessage });
        throw error;
    }
};

export const fetchProjectTasks = (projectId) => async (dispatch) => {
    dispatch({ type: 'FETCH_PROJECT_TASKS_REQUEST' });
    try {
        const response = await ProjectService.getProjectTasks(projectId);
        dispatch({
            type: 'FETCH_PROJECT_TASKS_SUCCESS',
            payload: {
                projectId,
                tasks: response.data
            }
        });
        return response.data;
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message;
        dispatch({ type: 'FETCH_PROJECT_TASKS_FAILURE', payload: errorMessage });
        throw error;
    }
};

export const createTask = (projectId, taskData) => async (dispatch) => {
    dispatch({ type: 'CREATE_TASK_REQUEST' });
    try {
        const response = await ProjectService.createTask(projectId, taskData);
        dispatch({ type: 'CREATE_TASK_SUCCESS', payload: response.data });
        return response.data;
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message;
        dispatch({ type: 'CREATE_TASK_FAILURE', payload: errorMessage });
        throw error;
    }
};

export const updateTask = (taskId, taskData) => async (dispatch) => {
    dispatch({ type: 'UPDATE_TASK_REQUEST' });
    try {
        const response = await ProjectService.updateTask(taskId, taskData);
        dispatch({ type: 'UPDATE_TASK_SUCCESS', payload: response.data });
        return response.data;
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message;
        dispatch({ type: 'UPDATE_TASK_FAILURE', payload: errorMessage });
        throw error;
    }
};

export const deleteTask = (taskId) => async (dispatch) => {
    dispatch({ type: 'DELETE_TASK_REQUEST' });
    try {
        await ProjectService.deleteTask(taskId);
        dispatch({ type: 'DELETE_TASK_SUCCESS', payload: taskId });
        return taskId;
    } catch (error) {
        const errorMessage = error.response?.data?.message || error.message;
        dispatch({ type: 'DELETE_TASK_FAILURE', payload: errorMessage });
        throw error;
    }
};
