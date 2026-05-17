import api from "../api/client";

export const rankService = {
  getCurrentRank: () =>
    api.get("/rank/current"),

  getRankDetails: (rankId: string) =>
    api.get(`/rank/${rankId}`),

  getRankHistory: (page = 1, limit = 20) =>
    api.get("/rank/history", { params: { page, limit } }),

  getRankRequirements: () =>
    api.get("/rank/requirements"),

  getQualificationStatus: () =>
    api.get("/rank/qualification-status"),

  getLeaderboard: (page = 1, limit = 50, period?: string) =>
    api.get("/rank/leaderboard", { params: { page, limit, period } }),

  getRankProgression: () =>
    api.get("/rank/progression"),
};
