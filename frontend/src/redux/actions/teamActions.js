import axiosInstance from '../../axios';



// Create Team
export const CREATE_TEAM_REQUEST = 'CREATE_TEAM_REQUEST';
export const CREATE_TEAM_SUCCESS = 'CREATE_TEAM_SUCCESS';
export const CREATE_TEAM_FAILURE = 'CREATE_TEAM_FAILURE';

// Update Team
export const UPDATE_TEAM_REQUEST = 'UPDATE_TEAM_REQUEST';
export const UPDATE_TEAM_SUCCESS = 'UPDATE_TEAM_SUCCESS';
export const UPDATE_TEAM_FAILURE = 'UPDATE_TEAM_FAILURE';

// Delete Team
export const DELETE_TEAM_REQUEST = 'DELETE_TEAM_REQUEST';
export const DELETE_TEAM_SUCCESS = 'DELETE_TEAM_SUCCESS';
export const DELETE_TEAM_FAILURE = 'DELETE_TEAM_FAILURE';



export const createTeam = (teamData) => {
  return async (dispatch) => {
    dispatch({ type: CREATE_TEAM_REQUEST });
    try {
      const response = await axiosInstance.post('teams', teamData);
      dispatch({
        type: CREATE_TEAM_SUCCESS,
        payload: response.data
      });
    } catch (error) {
      dispatch({
        type: CREATE_TEAM_FAILURE,
        payload: error.message
      });
    }
  };
};

export const updateTeam = (teamId, teamData) => {
  return async (dispatch) => {
    dispatch({ type: UPDATE_TEAM_REQUEST });
    try {
      const response = await axiosInstance.put(`teams/${teamId}`, teamData);
      dispatch({
        type: UPDATE_TEAM_SUCCESS,
        payload: response.data
      });
    } catch (error) {
      dispatch({
        type: UPDATE_TEAM_FAILURE,
        payload: error.message
      });
    }
  };
};

export const deleteTeam = (teamId) => {
  return async (dispatch) => {
    dispatch({ type: DELETE_TEAM_REQUEST });
    try {
      await axiosInstance.delete(`teams/${teamId}`);
      dispatch({
        type: DELETE_TEAM_SUCCESS,
        payload: teamId
      });
    } catch (error) {
      dispatch({
        type: DELETE_TEAM_FAILURE,
        payload: error.message
      });
    }
  };
};