// index.js
import React from "react";
import ReactDOM from "react-dom/client";
import { GoogleOAuthProvider } from "@react-oauth/google";
import AuthProvider from "./provider/authProvider";
import App from "./App"; // Import your main App component
import "./index.css"; // Import your global styles
import "./i18n";
const root = ReactDOM.createRoot(document.getElementById("root"));

root.render(
  <React.StrictMode>
    <GoogleOAuthProvider clientId="78269826480-frjk23rtnfkat02qhbk4nrgoauf0u1m9.apps.googleusercontent.com">
      <AuthProvider>
        <App /> {/* App now contains the RouterProvider */}
      </AuthProvider>
    </GoogleOAuthProvider>
  </React.StrictMode>
);
