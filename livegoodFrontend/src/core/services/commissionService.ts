import api from "../api/client";

export const commissionService = {
  getCommissions: (page = 1, limit = 20, filters?: any) =>
    api.get("/commissions", { params: { page, limit, ...filters } }),

  getCommissionDetails: (id: string) =>
    api.get(`/commissions/${id}`),

  getEarnings: () =>
    api.get("/commissions/earnings"),

  getEarningsByType: (type: string) =>
    api.get(`/commissions/earnings/type/${type}`),

  getBonusHistory: (bonusType?: string) =>
    api.get("/commissions/bonus-history", { params: bonusType ? { type: bonusType } : {} }),

  getMonthlyEarnings: () =>
    api.get("/commissions/monthly-earnings"),

  getYearlyEarnings: () =>
    api.get("/commissions/yearly-earnings"),

  getEarningsReport: (startDate: string, endDate: string) =>
    api.get("/commissions/report", { params: { start_date: startDate, end_date: endDate } }),
};
