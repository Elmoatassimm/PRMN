import { 
    INVITE_PM_PENDING,
    INVITE_PM_SUCCESS, 
    INVITE_PM_FAIL 
} from '../actions/inviteProjectManagerAction';

const initialState = {
    loading: false,
    error: null,
    success: false,
    invitation: null
};

const inviteProjectManagerReducer = (state = initialState, action) => {
    switch (action.type) {
        case INVITE_PM_PENDING:
            return {
                ...state,
                loading: true,
                error: null,
                success: false
            };
        case INVITE_PM_SUCCESS:
            return {
                ...state,
                loading: false,
                success: true,
                invitation: action.payload.invitation,
                error: null
            };
        case INVITE_PM_FAIL:
            return {
                ...state,
                loading: false,
                error: action.payload,
                success: false
            };
        case 'RESET_INVITE_STATE':
            return {
                ...initialState
            };
        default:
            return state;
    }
};

export default inviteProjectManagerReducer;