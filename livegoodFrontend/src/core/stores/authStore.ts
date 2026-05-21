import { reactive, watch } from "vue";
import api from "../api/client";

interface User {
  id: string;
  email: string;
  name: string;
  avatar_url?: string;
  role: string;
  permissions?: string[];
}

interface AuthState {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  error: string | null;
}

// Initial state from localStorage
const storedUser = localStorage.getItem("user_data");
const storedToken = localStorage.getItem("auth_token");

const state = reactive<AuthState & {
  setUser: (user: User | null) => void;
  setToken: (token: string | null) => void;
  setLoading: (loading: boolean) => void;
  setError: (error: string | null) => void;
  login: (email: string, password: string) => Promise<void>;
  register: (data: any) => Promise<void>;
  logout: () => void;
  fetchCurrentUser: () => Promise<void>;
  checkAuth: () => Promise<boolean>;
}>({
  user: storedUser ? JSON.parse(storedUser) : null,
  token: storedToken,
  isAuthenticated: !!storedToken,
  isLoading: false,
  error: null,

  setUser(user) {
    this.user = user;
    if (user) {
      localStorage.setItem("user_data", JSON.stringify(user));
    } else {
      localStorage.removeItem("user_data");
    }
  },

  setToken(token) {
    this.token = token;
    this.isAuthenticated = !!token;
    if (token) {
      localStorage.setItem("auth_token", token);
    } else {
      localStorage.removeItem("auth_token");
    }
  },

  setLoading(isLoading) {
    this.isLoading = isLoading;
  },

  setError(error) {
    this.error = error;
  },

  async login(email, password) {
    try {
      this.isLoading = true;
      this.error = null;
      
      const response = await api.post("/auth/login", {
        email,
        password,
        remember: true,
      });

      const { access_token, user: rawUser } = response.data.data || response.data;
      
      const user = {
        ...rawUser,
        name: rawUser.full_name || `${rawUser.prenom} ${rawUser.nom}`,
        role: rawUser.role?.value || rawUser.role
      };
      
      this.setToken(access_token);
      this.setUser(user);
      this.isLoading = false;
    } catch (error: any) {
      const errorMsg = error.response?.data?.message || "Login failed";
      this.error = errorMsg;
      this.isLoading = false;
      this.isAuthenticated = false;
      throw error;
    }
  },

  async register(data) {
    try {
      this.isLoading = true;
      this.error = null;
      
      const response = await api.post("/auth/register", data);
      const { access_token, user: rawUser } = response.data.data || response.data;
      
      const user = {
        ...rawUser,
        name: rawUser.full_name || `${rawUser.prenom} ${rawUser.nom}`,
        role: rawUser.role?.value || rawUser.role
      };
      
      this.setToken(access_token);
      this.setUser(user);
      this.isLoading = false;
    } catch (error: any) {
      const errorMsg = error.response?.data?.message || "Registration failed";
      this.error = errorMsg;
      this.isLoading = false;
      throw error;
    }
  },

  logout() {
    this.setToken(null);
    this.setUser(null);
    this.error = null;
  },

  async fetchCurrentUser() {
    try {
      if (!this.token) {
        this.isAuthenticated = false;
        return;
      }

      const response = await api.get("/auth/me");
      const data = response.data.data || response.data;
      
      const user = {
        ...data,
        name: data.full_name || `${data.prenom} ${data.nom}`,
        role: data.role?.value || data.role
      };
      
      this.setUser(user);
      this.isAuthenticated = true;
    } catch (error) {
      console.error("Failed to fetch current user:", error);
      this.logout();
    }
  },

  async checkAuth() {
    if (!this.token) {
      this.isAuthenticated = false;
      return false;
    }

    try {
      this.isLoading = true;
      await this.fetchCurrentUser();
      this.isLoading = false;
      return true;
    } catch (error) {
      this.isLoading = false;
      this.isAuthenticated = false;
      return false;
    }
  },
});

// Singleton hook to match the existing usage pattern
export const useAuthStore = () => state;
