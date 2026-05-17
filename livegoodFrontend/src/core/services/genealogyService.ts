import api from "../api/client";

export const genealogyService = {
  getUnilevelTree: (userId?: string) =>
    api.get("/genealogy/tree", { params: userId ? { user_id: userId } : {} }),

  getMatrixTree: (userId?: string) =>
    api.get("/genealogy/matrix/tree", { params: userId ? { user_id: userId } : {} }),

  getMatrixNode: (nodeId: string) =>
    api.get(`/genealogy/matrix/node/${nodeId}`),

  getNodeDetails: (nodeId: string) =>
    api.get(`/genealogy/node/${nodeId}`),

  searchMember: (query: string) =>
    api.get("/genealogy/search", { params: { q: query } }),

  getDownline: (userId: string, level?: number) =>
    api.get(`/genealogy/downline/${userId}`, { params: level ? { level } : {} }),

  getUpline: (userId: string) =>
    api.get(`/genealogy/upline/${userId}`),
};
