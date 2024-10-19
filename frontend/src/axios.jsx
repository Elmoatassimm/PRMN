import axios from "axios";
import i18n from "./i18n"; // Import the i18n configuration

const axiosInstance = axios.create({
  baseURL: "http://127.0.0.1:8000", // Change this to your backend URL
});

// Add a request interceptor
axiosInstance.interceptors.request.use((config) => {
  const lang = i18n.language || "en"; // Get current language from i18n
  config.params = { ...config.params, lang }; // Append lang parameter
  return config;
});

export default axiosInstance;
