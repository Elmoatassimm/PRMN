import inviteProjectManagerService from '../services/inviteProjectManagerService';

// Action Types
export const INVITE_PM_PENDING = 'INVITE_PM_PENDING';
export const INVITE_PM_SUCCESS = 'INVITE_PM_SUCCESS';
export const INVITE_PM_FAIL = 'INVITE_PM_FAIL';

// Action Creators
export const invitePMPending = () => ({
    type: INVITE_PM_PENDING
});

export const invitePMSuccess = (data) => ({
    type: INVITE_PM_SUCCESS,
    payload: data
});

export const invitePMFail = (error) => ({
    type: INVITE_PM_FAIL,
    payload: error
});

// Async Action Creator
export const inviteProjectManager = (inviteData) => {
    return async (dispatch) => {
        dispatch(invitePMPending());
        try {
            const response = await inviteProjectManagerService.inviteProjectManager({
                email: inviteData.email,
                projectId: inviteData.projectId
            });
            dispatch(invitePMSuccess(response.data));
            return response.data;
        } catch (error) {
            dispatch(invitePMFail(error.response?.data || 'An error occurred'));
            throw error;
        }
    };
};