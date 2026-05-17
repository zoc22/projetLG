import api from "../api/client";

export const paymentService = {
  getWithdrawalRequests: (page = 1, limit = 20) =>
    api.get("/payments/withdrawals", { params: { page, limit } }),

  getWithdrawalDetails: (id: string) =>
    api.get(`/payments/withdrawals/${id}`),

  createWithdrawalRequest: (data: any) =>
    api.post("/payments/withdrawals", data),

  cancelWithdrawalRequest: (id: string) =>
    api.post(`/payments/withdrawals/${id}/cancel`),

  getPaymentMethods: () =>
    api.get("/payments/methods"),

  addPaymentMethod: (data: any) =>
    api.post("/payments/methods", data),

  updatePaymentMethod: (id: string, data: any) =>
    api.put(`/payments/methods/${id}`, data),

  deletePaymentMethod: (id: string) =>
    api.delete(`/payments/methods/${id}`),

  setDefaultPaymentMethod: (id: string) =>
    api.post(`/payments/methods/${id}/default`),

  getWithdrawalFees: () =>
    api.get("/payments/withdrawal-fees"),

  getTransactionHistory: (page = 1, limit = 20) =>
    api.get("/payments/transactions", { params: { page, limit } }),
};
