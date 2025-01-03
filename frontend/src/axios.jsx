import axios from "axios";
import i18n from "./i18n"; // Import the i18n configuration

const axiosInstance = axios.create({
  baseURL: "http://127.0.0.1:8000/api/v1/", // Replace with your backend URL
});

// Add a request interceptor
axiosInstance.interceptors.request.use((config) => {
  // Get current language from i18n
  const lang = i18n.language || "en";
  
  // Get the token from localStorage
  const token = localStorage.getItem("token");

  // Add the token to the Authorization header if it exists
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  // Append lang parameter
  config.params = { ...config.params, lang };

  return config;
}, (error) => {
  // Handle request errors
  return Promise.reject(error);
});

export default axiosInstance;