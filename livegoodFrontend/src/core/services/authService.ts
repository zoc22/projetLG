import api from "../api/client";

export const authService = {
  login: (email: string, password: string) =>
    api.post("/auth/login", { email, password, remember: true }),

  register: (data: any) =>
    api.post("/auth/register", data),

  logout: () =>
    api.post("/auth/logout"),

  getCurrentUser: () =>
    api.get("/auth/me"),

  refreshToken: () =>
    api.post("/auth/refresh"),

  forgotPassword: (email: string) =>
    api.post("/auth/forgot-password", { email }),

  resetPassword: (token: string, email: string, password: string) =>
    api.post("/auth/reset-password", { token, email, password }),

  verifyEmail: (email: string, code: string) =>
    api.post("/auth/verify-email", { email, code }),

  enable2FA: () =>
    api.post("/auth/2fa/enable"),

  verify2FA: (code: string) =>
    api.post("/auth/2fa/verify", { code }),

  get2FARecoveryCodes: () =>
    api.get("/auth/2fa/recovery-codes"),
};
