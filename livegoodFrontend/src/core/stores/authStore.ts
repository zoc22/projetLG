import { create } from "zustand";
import { persist } from "zustand/middleware";
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

  // Actions
  setUser: (user: User | null) => void;
  setToken: (token: string | null) => void;
  setLoading: (loading: boolean) => void;
  setError: (error: string | null) => void;
  
  login: (email: string, password: string) => Promise<void>;
  register: (data: any) => Promise<void>;
  logout: () => void;
  fetchCurrentUser: () => Promise<void>;
  checkAuth: () => Promise<boolean>;
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set, get) => ({
      user: null,
      token: null,
      isAuthenticated: false,
      isLoading: false,
      error: null,

      setUser: (user) => set({ user }),
      setToken: (token) => {
        set({ token });
        if (token) {
          localStorage.setItem("auth_token", token);
        }
      },
      setLoading: (isLoading) => set({ isLoading }),
      setError: (error) => set({ error }),

      login: async (email: string, password: string) => {
        try {
          set({ isLoading: true, error: null });
          
          const response = await api.post("/auth/login", {
            email,
            password,
            remember: true,
          });

          const { token, user } = response.data.data || response.data;
          
          set({
            token,
            user,
            isAuthenticated: true,
            isLoading: false,
          });
          
          localStorage.setItem("auth_token", token);
          localStorage.setItem("user_data", JSON.stringify(user));
        } catch (error: any) {
          const errorMsg = error.response?.data?.message || "Login failed";
          set({
            error: errorMsg,
            isLoading: false,
            isAuthenticated: false,
          });
          throw error;
        }
      },

      register: async (data: any) => {
        try {
          set({ isLoading: true, error: null });
          
          const response = await api.post("/auth/register", data);
          const { token, user } = response.data.data || response.data;
          
          set({
            token,
            user,
            isAuthenticated: true,
            isLoading: false,
          });
          
          localStorage.setItem("auth_token", token);
          localStorage.setItem("user_data", JSON.stringify(user));
        } catch (error: any) {
          const errorMsg = error.response?.data?.message || "Registration failed";
          set({
            error: errorMsg,
            isLoading: false,
          });
          throw error;
        }
      },

      logout: () => {
        localStorage.removeItem("auth_token");
        localStorage.removeItem("user_data");
        set({
          user: null,
          token: null,
          isAuthenticated: false,
          error: null,
        });
      },

      fetchCurrentUser: async () => {
        try {
          const token = localStorage.getItem("auth_token");
          if (!token) {
            set({ isAuthenticated: false });
            return;
          }

          const response = await api.get("/auth/me");
          const user = response.data.data || response.data;
          
          set({
            user,
            isAuthenticated: true,
          });
          
          localStorage.setItem("user_data", JSON.stringify(user));
        } catch (error) {
          console.error("Failed to fetch current user:", error);
          set({
            isAuthenticated: false,
            user: null,
            token: null,
          });
          localStorage.removeItem("auth_token");
          localStorage.removeItem("user_data");
        }
      },

      checkAuth: async (): Promise<boolean> => {
        const token = localStorage.getItem("auth_token");
        if (!token) {
          set({ isAuthenticated: false });
          return false;
        }

        try {
          set({ isLoading: true });
          await get().fetchCurrentUser();
          set({ isLoading: false });
          return true;
        } catch (error) {
          set({ isLoading: false, isAuthenticated: false });
          return false;
        }
      },
    }),
    {
      name: "livegood-auth",
      partialize: (state) => ({
        token: state.token,
        user: state.user,
        isAuthenticated: state.isAuthenticated,
      }),
    }
  )
);
