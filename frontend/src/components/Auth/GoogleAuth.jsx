import React, { useState,useRef } from "react";
import { GoogleLogin } from "@react-oauth/google";
import { jwtDecode } from "jwt-decode";
import axios from "axios";
import { useTranslation } from "react-i18next";

import { useAuth } from "../../provider/authProvider";
import axiosInstance from "../../axios";

 // Make sure to replace with your actual client ID

const GoogleAuth = ({ onLoginSuccess, onLoginError, selectedRole }) => {
  const { t, i18n } = useTranslation();
  const { setToken } = useAuth();
  const [responseMessage, setResponseMessage] = useState("");
  const [errorDetails, setErrorDetails] = useState([]);
  const googleLoginButtonRef = useRef(null);
  const handleButtonClick = () => {
    // Programmatically trigger the Google login button click
    if (googleLoginButtonRef.current) {
      googleLoginButtonRef.current.click();
    }
  };
  const handleGoogleLoginSuccess = async (credentialResponse) => {
    try {
      if (!credentialResponse?.credential) {
        throw new Error("Google credential not found.");
      }

       // Decode the token to get user info
       const decoded = jwtDecode(credentialResponse.credential);
       const userData = {
         email: decoded.email,
         name: decoded.name,
         id: decoded.sub,
         avatar: decoded.picture,
         role: selectedRole,
         token: credentialResponse.credential, // Include the Google OAuth token
       };

      const response = await axiosInstance.post(
        "auth/google-auth",
        userData
      );

      setToken(response.data.data.access_token); // Adjust based on your actual token structure
      setResponseMessage(response.data.message);
      onLoginSuccess();

      setErrorDetails([]);
    } catch (error) {
      
      let errorMessage = "Google login failed. Please try again.";
      const validationErrors = [];

      if (error.response?.data) {
        errorMessage = error.response.data.message || errorMessage;

        if (error.response.data.errors) {
          for (const key in error.response.data.errors) {
            validationErrors.push(...error.response.data.errors[key]);
          }
        }
      }

      console.error("Login error:", error); // Log the error for debugging
      setResponseMessage(errorMessage);
      setErrorDetails(validationErrors);
      onLoginError(errorMessage);
    }
  };

  const handleGoogleLoginError = () => {
    const errorMessage = "Google login failed. Please try again.";
    setResponseMessage(errorMessage);
    setErrorDetails([]);
    onLoginError(errorMessage);
  };

  return (
    <>
      {/* Google Login Button */}
      <GoogleLogin
      theme="filled_blue"
      type="stander"
      size="large"
      text="continue_with"
      shape="circle"
      logo_alignment="center"
      width={100}
      locale={i18n.language}
      onSuccess={handleGoogleLoginSuccess}
      onError={handleGoogleLoginError}
    >
      
    </GoogleLogin>
      

      {responseMessage && (
        <div
          className={`response-message ${
            errorDetails.length > 0 ? "error" : "success"
          }`}
        >
          {responseMessage}
        </div>
      )}

      {errorDetails.length > 0 && (
        <div className="error-details">
          <ul>
            {errorDetails.map((error, index) => (
              <li key={index}>{error}</li>
            ))}
          </ul>
        </div>
      )}
    
    </>
  );
};

export default GoogleAuth;
