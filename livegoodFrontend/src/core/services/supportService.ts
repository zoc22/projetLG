import api from "../api/client";

export const supportService = {
  getTickets: (page = 1, limit = 20, status?: string) =>
    api.get("/support/tickets", { params: { page, limit, status } }),

  getTicketDetails: (id: string) =>
    api.get(`/support/tickets/${id}`),

  createTicket: (data: any) =>
    api.post("/support/tickets", data),

  replyToTicket: (ticketId: string, message: string) =>
    api.post(`/support/tickets/${ticketId}/replies`, { message }),

  closeTicket: (id: string) =>
    api.post(`/support/tickets/${id}/close`),

  reopenTicket: (id: string) =>
    api.post(`/support/tickets/${id}/reopen`),

  getTicketMessages: (ticketId: string, page = 1, limit = 50) =>
    api.get(`/support/tickets/${ticketId}/messages`, { params: { page, limit } }),

  getCategories: () =>
    api.get("/support/categories"),

  getFAQ: () =>
    api.get("/support/faq"),

  searchFAQ: (query: string) =>
    api.get("/support/faq/search", { params: { q: query } }),
};
