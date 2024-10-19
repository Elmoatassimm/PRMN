// src/components/Logout.js
import React from "react";
import axios from "axios";
import { useAuth } from "../provider/authProvider"; // Adjust the import path as necessary
import { useTranslation } from "react-i18next"; // Import useTranslation
import axiosInstance from "../axios";

const Logout = () => {
  const { logout: logoutFromContext } = useAuth();
  const { t } = useTranslation(); // Use translation hook

  const handleLogout = async () => {
    try {
      await axiosInstance.post(
        "/api/auth/logout",
        {},
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        }
      );
      // Call the logout function from context
      logoutFromContext(); // This will clear the token in context
      alert(t("logout.success")); // Translated logout success message
    } catch (error) {
      console.error("Logout failed:", error);
      alert(t("logout.failure")); // Translated logout failure message
    }
  };

  return <button onClick={handleLogout}>{t("logout.button")}</button>; // Translated button text
};

export default Logout;
