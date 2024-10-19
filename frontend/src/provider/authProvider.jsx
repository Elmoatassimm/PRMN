import axios from "axios";
import { createContext, useContext, useEffect, useMemo, useState } from "react";

const AuthContext = createContext();

const AuthProvider = ({ children }) => {
  const [token, setToken_] = useState(localStorage.getItem("token"));
  const [refreshing, setRefreshing] = useState(false);

  const setToken = (newToken) => {
    setToken_(newToken);
  };

  useEffect(() => {
    if (token) {
      axios.defaults.headers.common["Authorization"] = "Bearer " + token;
      localStorage.setItem("token", token);
    } else {
      delete axios.defaults.headers.common["Authorization"];
      localStorage.removeItem("token");
    }
  }, [token]);

  const refreshToken = async () => {
    try {
      setRefreshing(true);
      const response = await axios.post(
        `${process.env.REACT_APP_API_URL}/api/auth/refresh`
      );
      const newToken = response.data.data.access_token; // Adjusted to match your API response structure
      setToken(newToken);
    } catch (error) {
      console.error("Token refresh failed:", error);
      setToken(null); // Clear the token if refresh fails
    } finally {
      setRefreshing(false);
    }
  };

  const logout = () => {
    setToken(null); // Clear the token
   
  };

  useEffect(() => {
    let refreshTimer;
    if (token) {
      const tokenData = JSON.parse(atob(token.split(".")[1]));
      const expiryTime = tokenData.exp * 1000 - Date.now();

      refreshTimer = setTimeout(() => {
        refreshToken();
      }, expiryTime - 60000); // Refresh 1 minute before expiration
    }

    return () => clearTimeout(refreshTimer);
  }, [token]);

  const contextValue = useMemo(
    () => ({
      token,
      setToken,
      refreshToken,
      logout,
      refreshing,
    }),
    [token, refreshing]
  );

  return (
    <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
  );
};

export const useAuth = () => {
  return useContext(AuthContext);
};

export default AuthProvider;
