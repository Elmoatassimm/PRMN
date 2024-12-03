const initialState = {
    projects: [],
    currentProject: null,
    loading: false,
    error: null,
};

const projectReducer = (state = initialState, action) => {
    switch (action.type) {
        case 'CREATE_PROJECT_REQUEST':
            return { ...state, loading: true, error: null };
        case 'CREATE_PROJECT_SUCCESS':
            return { ...state, loading: false, projects: [action.payload] };
        case 'CREATE_PROJECT_FAILURE':
            return { ...state, loading: false, error: action.payload };
        
        case 'FETCH_PROJECTS_REQUEST':
            return { ...state, loading: true, error: null };
        case 'FETCH_PROJECTS_SUCCESS':
            return { ...state, loading: false, projects: action.payload };
        case 'FETCH_PROJECTS_FAILURE':
            return { ...state, loading: false, error: action.payload };
            
        case 'FETCH_PROJECT_REQUEST':
            return { ...state, loading: true, error: null };
        case 'FETCH_PROJECT_SUCCESS':
            return { ...state, loading: false, currentProject: action.payload };
        case 'FETCH_PROJECT_FAILURE':
            return { ...state, loading: false, error: action.payload };
            
        case 'FETCH_PROJECT_TASKS_REQUEST':
            return { ...state, loading: true, error: null };
        case 'FETCH_PROJECT_TASKS_SUCCESS':
            return {
                ...state,
                loading: false,
                projects: state.projects.map(project =>
                    project.id === action.payload.projectId
                        ? { ...project, tasks: action.payload.tasks }
                        : project
                )
            };
        case 'FETCH_PROJECT_TASKS_FAILURE':
            return { ...state, loading: false, error: action.payload };
            
        case 'CREATE_TASK_REQUEST':
            return { ...state, loading: true, error: null };
        case 'CREATE_TASK_SUCCESS':
            return {
                ...state,
                loading: false,
                projects: state.projects.map(project =>
                    project.id === action.payload.project_id
                        ? { ...project, tasks: [...(project.tasks || []), action.payload] }
                        : project
                )
            };
        case 'CREATE_TASK_FAILURE':
            return { ...state, loading: false, error: action.payload };
            
        case 'UPDATE_TASK_REQUEST':
            return { ...state, loading: true, error: null };
        case 'UPDATE_TASK_SUCCESS':
            return {
                ...state,
                loading: false,
                projects: state.projects.map(project =>
                    project.id === action.payload.project_id
                        ? {
                            ...project,
                            tasks: project.tasks.map(task =>
                                task.id === action.payload.id ? action.payload : task
                            )
                        }
                        : project
                )
            };
        case 'UPDATE_TASK_FAILURE':
            return { ...state, loading: false, error: action.payload };

        case 'DELETE_TASK_REQUEST':
            return { ...state, loading: true, error: null };
        case 'DELETE_TASK_SUCCESS':
            return {
                ...state,
                loading: false,
                projects: state.projects.map(project => ({
                    ...project,
                    tasks: project.tasks?.filter(task => task.id !== action.payload) || []
                }))
            };
        case 'DELETE_TASK_FAILURE':
            return { ...state, loading: false, error: action.payload };

        default:
            return state;
    }
};

export default projectReducer;      
