import api from "../api/client";

export const affiliationService = {
  getDashboard: () =>
    api.get("/affiliation/dashboard"),

  getProfile: () =>
    api.get("/affiliation/profile"),

  updateProfile: (data: any) =>
    api.put("/affiliation/profile", data),

  getSubscriptions: (page = 1, limit = 20) =>
    api.get("/affiliation/subscriptions", { params: { page, limit } }),

  getRankHistory: () =>
    api.get("/affiliation/rank-history"),

  getCurrentRank: () =>
    api.get("/affiliation/current-rank"),
};
