import React, { useReducer } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../provider/authProvider";
import GoogleAuth from "../Componentssss/Auth/GoogleAuth";
import InputError from "../Componentssss/InputErrors";
import axiosInstance from "../axios";
import { useTranslation } from "react-i18next";
import { Button } from "../components/ui/button"; // Assuming you have a button component
import { Input } from "../components/ui/input"; // Assuming you have an input component
import { Label } from "../components/ui/label"; // Assuming you have a label component
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group"; // Shadcn UI Radio Group component
 // Adjust the path as needed
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
const initialState = {
  name: "",
  email: "",
  password: "",
  confirmPassword: "",
  role: "team_member", // Single role for radio buttons
  errors: {},
  loading: false,
};

function reducer(state, action) {
  switch (action.type) {
    case "SET_FIELD":
      return { ...state, [action.field]: action.value };
    case "SET_ROLE":
      return { ...state, role: action.role };
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

const Register = () => {
  const { t } = useTranslation();
  const { setToken } = useAuth();
  const navigate = useNavigate();
  const [state, dispatch] = useReducer(reducer, initialState);

  const { name, email, password, confirmPassword, role, errors, loading } = state;

  const handleSubmit = async (e) => {
    e.preventDefault();
    dispatch({ type: "RESET_ERRORS" });
    dispatch({ type: "SET_LOADING", loading: true });

    try {
      const response = await axiosInstance.post(`/api/auth/register`, {
        name,
        email,
        password,
        password_confirmation: confirmPassword,
        role, // Send the selected role
      });
      setToken(response.data.data.access_token);
      navigate("/dashboard", { replace: true });
    } catch (err) {
      if (err.response) {
        const { message, errors: validationErrors } = err.response.data;
        if (validationErrors && Object.keys(validationErrors).length > 0) {
          dispatch({ type: "SET_ERRORS", errors: validationErrors });
        } else {
          dispatch({
            type: "SET_ERRORS",
            errors: {
              general: message || "Registration failed. Please try again.",
            },
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

  const handleGoogleLoginSuccess = () => {
    navigate("/dashboard", { replace: true });
  };

  const handleGoogleLoginError = (errorMessage) => {
    console.error("Google Login Error:", errorMessage);
  };

  return (
    <Card className="max-w-md mx-auto mt-2 text-xs">
       <CardHeader className="">
        <CardTitle>{t("register.title")}</CardTitle> {/* Translate title */}
        <CardDescription>{t("register.description")}</CardDescription> {/* Optional description */}
      </CardHeader> {/* Use CarsHeader component */}
      <CardContent>
         <form onSubmit={handleSubmit}>
          <InputError error={errors.general} />
          <div className="mb-2">
            <Label htmlFor="name">{t("name")}</Label>
            <Input
              id="name"
              type="text"
              value={name}
              onChange={(e) =>
                dispatch({
                  type: "SET_FIELD",
                  field: "name",
                  value: e.target.value,
                })
              }
              required
            />
            <InputError error={errors.name && errors.name[0]} />
          </div>

          <div className="mb-2">
            <Label htmlFor="email">{t("email")}</Label>
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
            />
            <InputError error={errors.email && errors.email[0]} />
          </div>

          <div className="mb-2">
            <Label htmlFor="password">{t("password")}</Label>
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
            />
            <InputError error={errors.password && errors.password[0]} />
          </div>

          <div className="mb-2">
            <Label htmlFor="confirmPassword">{t("confirm_password")}</Label>
            <Input
              id="confirmPassword"
              type="password"
              value={confirmPassword}
              onChange={(e) =>
                dispatch({
                  type: "SET_FIELD",
                  field: "confirmPassword",
                  value: e.target.value,
                })
              }
              required
            />
          </div>

          <div className="mb-2">
            <Label>{t("role")}</Label>
            <RadioGroup
              value={role}
              onValueChange={(value) => dispatch({ type: "SET_ROLE", role: value })}
            >
              <div className="flex flex-col">
                <div className="flex items-center mb-2">
                  <RadioGroupItem value="admin" id="admin" />
                  <Label htmlFor="admin" className="ml-2">
                    {t("admin")}
                  </Label>
                </div>
                <div className="flex items-center mb-2">
                  <RadioGroupItem value="project_manager" id="project_manager" />
                  <Label htmlFor="project_manager" className="ml-2">
                    {t("project_manager")}
                  </Label>
                </div>
                <div className="flex items-center mb-2">
                  <RadioGroupItem value="team_member" id="team_member" />
                  <Label htmlFor="team_member" className="ml-2">
                    {t("team_member")}
                  </Label>
                </div>
              </div>
            </RadioGroup>
            <InputError error={errors.role && errors.role[0]} />
          </div>

          <div className="flex justify-evenly items-center">
            <Button type="submit" disabled={loading} className="w-1/2 mx-1">
              {loading ? "registering" : t("register.register")}
            </Button>
            <GoogleAuth
              onLoginSuccess={handleGoogleLoginSuccess}
              onLoginError={handleGoogleLoginError}
            />
          </div>
          
          <p className="text-sm mt-3"> {t("register.have_account")}
        <Link to="/login" className="underline mx-1">
          {t("register.register")} {/* Translate link */}
        </Link></p>
          
        </form>
      </CardContent>
    </Card>
  );
};

export default Register;
