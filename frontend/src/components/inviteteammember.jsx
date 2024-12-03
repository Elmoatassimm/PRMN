import { useState } from 'react';
import axiosInstance from '../axios';
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Label } from "@/components/ui/label";

const InviteTeamMember = ({ teamId }) => {
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage('');
    setError('');

    try {
      const response = await axiosInstance.post('auth/invitedusers', {
        user_email: email,
        invitable_id: teamId,
        invitable_type: 'App\\Models\\Team'
      });

      setMessage('Invitation sent successfully!');
      setEmail('');
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to send invitation');
    }
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle>Invite Team Member</CardTitle>
      </CardHeader>
      <CardContent>
        {message && (
          <Alert className="mb-4" variant="success">
            <AlertDescription>{message}</AlertDescription>
          </Alert>
        )}
        
        {error && (
          <Alert className="mb-4" variant="destructive">
            <AlertDescription>{error}</AlertDescription>
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

            <Button type="submit" className="w-full">
              Send Invitation
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>
  );
};

export default InviteTeamMember;

