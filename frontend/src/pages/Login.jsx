import React, { useReducer } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../provider/authProvider";
import axios from "axios";
import GoogleAuth from "../components/Auth/GoogleAuth";
import InputError from "../components/InputErrors"; // Reuse InputError component
import axiosInstance from "../axios";
import { useTranslation } from "react-i18next";
import { Button } from "../components/ui/button"; // Assuming you have a button component
import { Input } from "../components/ui/input"; // Assuming you have an input component
import { Label } from "../components/ui/label"; // Correct import for the Label component
 // Import ShadCN Label
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card"; // Import ShadCN Card components

// Define initial state for the reducer
const initialState = {
  email: "",
  password: "",
  errors: {},
  loading: false,
};

// Reducer function to manage state updates
function reducer(state, action) {
  switch (action.type) {
    case "SET_FIELD":
      return { ...state, [action.field]: action.value };
    case "SET_ERRORS":
      return { ...state, errors: action.errors };
    case "SET_LOADING":
      return { ...state, loading: action.loading };
    case "RESET_ERRORS":
      return { ...state, errors: {} };
    default:
      return state;
  }
}

const Login = () => {
  const { t } = useTranslation();
  const { setToken } = useAuth();
  const navigate = useNavigate();
  const [state, dispatch] = useReducer(reducer, initialState);

  const { email, password, errors, loading } = state; // Destructure state

  const handleSubmit = async (e) => {
    e.preventDefault();
    dispatch({ type: "RESET_ERRORS" }); // Clear previous errors
    dispatch({ type: "SET_LOADING", loading: true }); // Set loading state

    try {
      const response = await axiosInstance.post(
        "auth/login", // The base URL is already set in the axios instance
        { email, password }
      );
      setToken(response.data.access_token);
      console.log("login success");
      
      // Navigate based on user role
      if (response.data.user.role === 'admin') {
        navigate("/dashboard", { replace: true });
      } else if (response.data.user.role === 'team_member') {
        navigate("/team-member-dashboard", { replace: true });
      }
    } catch (err) {
      if (err.response) {
        const { message, errors: validationErrors } = err.response.data;
        if (validationErrors && Object.keys(validationErrors).length > 0) {
          dispatch({ type: "SET_ERRORS", errors: validationErrors });
        } else {
          dispatch({
            type: "SET_ERRORS",
            errors: { general: message || "Login failed. Please try again." },
          });
        }
      } else {
        dispatch({
          type: "SET_ERRORS",
          errors: { general: "Network error. Please try again." },
        });
      }
    } finally {
      dispatch({ type: "SET_LOADING", loading: false });
    }
  };

  const handleGoogleLoginSuccess = (response) => {
    console.log("Google login success");
    // Navigate based on user role from Google response
    if (response.user.role === 'admin') {
      navigate("/dashboard", { replace: true });
    } else if (response.user.role === 'team_member') {
      navigate("/team-member-dashboard", { replace: true });
    }
  };

  const handleGoogleLoginError = (errorMessage) => {
    if (errorMessage === "Please select a role") {
      navigate("/register"); // Navigate to the test page
    }
    dispatch({ type: "SET_ERRORS", errors: { general: errorMessage } });
  };

  return (
    <Card className="mx-auto mt-14 max-w-sm">
      <CardHeader>
        <CardTitle>{t("login.title")}</CardTitle> {/* Translate title */}
        <CardDescription>{t("login.description")}</CardDescription> {/* Optional description */}
      </CardHeader>
      <CardContent>
        <form onSubmit={handleSubmit} className="grid gap-4">
          <div className="form-group">
            <InputError error={errors.general} />
            <Label htmlFor="email">{t("login.email_label")}</Label> {/* Translate label */}
            <Input
              id="email"
              type="email"
              value={email}
              onChange={(e) =>
                dispatch({
                  type: "SET_FIELD",
                  field: "email",
                  value: e.target.value,
                })
              }
              required
              placeholder={t("login.email_placeholder")} // Translate placeholder
            />
            <InputError error={errors.email && errors.email[0]} />
          </div>

          <div className="form-group">
            <Label htmlFor="password">{t("login.password_label")}</Label> {/* Translate label */}
            <Input
              id="password"
              type="password"
              value={password}
              onChange={(e) =>
                dispatch({
                  type: "SET_FIELD",
                  field: "password",
                  value: e.target.value,
                })
              }
              required
              placeholder={t("login.password_placeholder")} // Translate placeholder
            />
            <InputError error={errors.password && errors.password[0]} />
          </div>

          <div className="flex justify-between items-center">
  <Button type="submit" disabled={loading} className="w-1/2 mx-1">
    {loading ? (
      <span>
        {t("login.loading")} {/* Translate loading text */}
        <span className="spinner" />
      </span>
    ) : (
      t("login.submit") // Translate button text
    )}
  </Button>
  
  <GoogleAuth 
    onLoginSuccess={handleGoogleLoginSuccess}
    onLoginError={handleGoogleLoginError}
  />
</div>

        </form>

        {/* GoogleAuth Component */}
         <p className="text-sm mt-3"> {t("login.not_account")}
        <Link to="/register" className="underline">
          {t("header.register")} {/* Translate link */}
        </Link></p>
      </CardContent>
    </Card>
    
  );
};

export default Login;