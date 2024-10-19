// router.js
import { createBrowserRouter } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import Home from "./pages/Home"; // Assuming you have a Home component
// For 404 route
import Root from "./routes/root";

export const router = createBrowserRouter([
  {
    path: "/",
    element: <Root />,
    errorElement: <Root />,
    children: [
      {
        path: "login",
        element: <Login />,
      },
      {
          path: "register",
          element: <Register />,
        },
    ],
  },
]);
