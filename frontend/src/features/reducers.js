import { combineReducers } from '@reduxjs/toolkit';
import projectReducer from './project/projectSlice';

const rootReducer = combineReducers({
  projects: projectReducer,
  // Add other reducers here as the application grows
});

export default rootReducer;
