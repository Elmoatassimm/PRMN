import React, { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { inviteProjectManager } from '../redux/actions/inviteProjectManagerAction';
import { useParams, useNavigate } from 'react-router-dom';
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Label } from "@/components/ui/label";

const InviteProjectManager = () => {
    const { projectId } = useParams();
    const [email, setEmail] = useState('');
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const { loading, error, success } = useSelector(state => state.projectManagerInvite);

    const handleSubmit = async (e) => {
        e.preventDefault();
        
            await dispatch(inviteProjectManager({
                email,
                projectId
            }));
            setEmail('');
            navigate('/dashboard');
        
    };

    return (
        <div className="max-w-md mx-auto mt-8">
            <Card>
                <CardHeader>
                    <CardTitle>Invite Project Manager</CardTitle>
                </CardHeader>
                <CardContent>
                    {error && (
                        <Alert className="mb-4" variant="destructive">
                            <AlertDescription>{error}</AlertDescription>
                        </Alert>
                    )}
                    
                    {success && (
                        <Alert className="mb-4" variant="success">
                            <AlertDescription>Invitation sent successfully!</AlertDescription>
                        </Alert>
                    )}

                    <form onSubmit={handleSubmit}>
                        <div className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="email">Email Address</Label>
                                <Input
                                    type="email"
                                    id="email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    placeholder="Enter email address"
                                    required
                                />
                            </div>

                            <Button 
                                type="submit"
                                disabled={loading}
                                className="w-full"
                            >
                                {loading ? 'Sending Invitation...' : 'Send Invitation'}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    );
};

export default InviteProjectManager;
