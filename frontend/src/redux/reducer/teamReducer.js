import {
  
  CREATE_TEAM_REQUEST,
  CREATE_TEAM_SUCCESS, 
  CREATE_TEAM_FAILURE,
  UPDATE_TEAM_REQUEST,
  UPDATE_TEAM_SUCCESS,
  UPDATE_TEAM_FAILURE,
  DELETE_TEAM_REQUEST,
  DELETE_TEAM_SUCCESS,
  DELETE_TEAM_FAILURE
} from '../actions/teamActions';

const initialState = {
  teams: [],
  loading: false,
  error: null
};

const teamReducer = (state = initialState, action) => {
  switch (action.type) {
    
    case CREATE_TEAM_REQUEST:
      return {
        ...state,
        loading: true,
        error: null
      };
    case CREATE_TEAM_SUCCESS:
      return {
        ...state,
        loading: false,
        teams: [...state.teams, action.payload]
      };
    case CREATE_TEAM_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.payload
      };
    case UPDATE_TEAM_REQUEST:
      return {
        ...state,
        loading: true,
        error: null
      };
    case UPDATE_TEAM_SUCCESS:
      return {
        ...state,
        loading: false,
        teams: state.teams.map(team =>
          team.id === action.payload.id ? action.payload : team
        )
      };
    case UPDATE_TEAM_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.payload
      };
    case DELETE_TEAM_REQUEST:
      return {
        ...state,
        loading: true,
        error: null
      };
    case DELETE_TEAM_SUCCESS:
      return {
        ...state,
        loading: false,
        teams: state.teams.filter(team => team.id !== action.payload)
      };
    case DELETE_TEAM_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.payload
      };
    default:
      return state;
  }
};

export default teamReducer;