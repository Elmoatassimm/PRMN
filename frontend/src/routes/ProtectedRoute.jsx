import React from "react";
import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "../provider/authProvider";
import Header from "../Componentssss/Header";
const ProtectedRoute = () => {
  const { token } = useAuth();

  if (!token) {
    <Navigate to="/login" replace />;
  }

  return (
    <>
      <Header />
      <Outlet />
    </>
  );
};

export { ProtectedRoute };
