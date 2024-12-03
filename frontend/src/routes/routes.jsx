import {
  RouterProvider,
  createBrowserRouter,
  Navigate,
} from "react-router-dom";
import { useAuth } from "../provider/authProvider";
import { ProtectedRoute } from "./ProtectedRoute";
import Home from "../pages/Home";
import Login from "../pages/Login";
import Register from "../pages/Register";
import PublicRoute from "./PublicRoute";
import CreateProject from "../pages/CreateProject";
import InviteProjectManager from "../components/InviteProjectmanager";
import Dashboard from "../pages/Dashboard";
import MemberDashboard from "../components/MemberDashboard";

const Routes = () => {
  const { token } = useAuth(); // Get the token to check if the user is authenticated

  // Routes accessible only to authenticated users
  const authenticatedRoutes = [
    {
      path: "/",
      element: <ProtectedRoute />, // Protect the route
      children: [
        {
          path: "/",
          element: <Dashboard />, // Set Dashboard as the default route
        },
        {
          path: "/createProject",
          element: <CreateProject />, // Home component for authenticated users
        },
        {
          path: "/inviteProjectManager/:projectId",
          element: <InviteProjectManager />, // Protected invite project manager route
        },
        {
          path: "/home",
          element: <Home />, // Alias for home
        },
        {
          path: "/profile",
          element: <div>User Profile</div>, // Replace with actual Profile component
        },
        {
          path: "/logout",
          element: <div>Logout</div>, // Handle logout logic here
        },
        {
          path: "/dashboard",
          element: <Dashboard />,
        },
        {
          path: "/team-member-dashboard",
          element: <MemberDashboard />, // Using Dashboard component for team members for now
        },
      ],
    },
  ];

  // Routes accessible to all users
  const publicRoutes = [
    {
      path: "/",
      element: <PublicRoute />, // Protect public routes as needed
      children: [
        {
          path: "/login",
          element: <Login />, // Login page
        },
        {
          path: "/home",
          element: <Home />, // Login page
        },
        {
          path: "/",
          element: <Home />, // Login page
        },
        {
          path: "/register",
          element: <Register />, // Registration page
        },
        {
          path: "*", // Catch-all route for public users
          element: <Navigate to="/login" replace />, // Redirect to login if not authenticated
        },
      ],
    },
  ];

  // Combine the routes based on whether the user is authenticated
  const routes = [
    ...(token ? authenticatedRoutes : publicRoutes), // Use authenticated routes if token exists
    {
      path: "*", // Catch-all route for all other cases
      element: <Navigate to="/" replace />, // Redirect to home for invalid paths
    },
  ];

  // Create the router with combined routes
  const router = createBrowserRouter(routes);

  // Provide the router configuration
  return <RouterProvider router={router} />;
};

export default Routes;
