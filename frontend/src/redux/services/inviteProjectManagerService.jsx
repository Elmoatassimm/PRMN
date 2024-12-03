import axiosInstance from '../../axios';

const inviteProjectManagerService = {
    inviteProjectManager: async (inviteData) => {
        const response = await axiosInstance.post('auth/invitedusers', {
            user_email: inviteData.email,
            invitable_id: inviteData.projectId,
            invitable_type: 'App\\Models\\Project', // Using double backslashes for escaping
        });
        return response;
    }
};

export default inviteProjectManagerService; 