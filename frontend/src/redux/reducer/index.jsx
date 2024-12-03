import { combineReducers } from 'redux';
import projectReducer from './projectReducer';
import inviteProjectManagerReducer from './inviteProjectManagerReducer';
import teamReducer from './teamReducer';
const rootReducer = combineReducers({
    project: projectReducer,
    projectManagerInvite: inviteProjectManagerReducer,
    team: teamReducer,
    // Add other reducers here
});

export default rootReducer;
