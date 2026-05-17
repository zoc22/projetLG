import axios from "axios";
import { useAuthStore } from "../stores/authStore";

const baseURL = import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api";

export const api = axios.create({
  baseURL,
  headers: {
    "Content-Type": "application/json",
  },
});

// Request interceptor - Add auth token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("auth_token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor - Handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user_data");
      // Redirect to login only if not already there
      if (window.location.pathname !== "/auth/login") {
        window.location.href = "/auth/login";
      }
    } else if (error.response?.status === 403) {
      console.error("Forbidden: You don't have permission to access this resource");
    } else if (error.response?.status === 500) {
      console.error("Server error:", error.response.data?.message);
    }
    return Promise.reject(error);
  }
);

export default api;
