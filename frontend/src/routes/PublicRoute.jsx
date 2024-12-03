import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "../provider/authProvider";
import Header from "../components/Header";
const PublicRoute = () => {
  const { token } = useAuth();

  if (token) {
    // If the user is already logged in, redirect to the home page
    return <Navigate to="/dashboard" replace />;
  }

  // Otherwise, render the public (login/register) page
  return (
    <>
      <Header />
      <Outlet />
    </>
  );
};

export default PublicRoute;
