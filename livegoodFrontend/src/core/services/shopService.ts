import api from "../api/client";

export const shopService = {
  getProducts: (page = 1, limit = 20, filters?: any) =>
    api.get("/shop/products", { params: { page, limit, ...filters } }),

  getProductDetails: (id: string) =>
    api.get(`/shop/products/${id}`),

  searchProducts: (query: string) =>
    api.get("/shop/products/search", { params: { q: query } }),

  getCategories: () =>
    api.get("/shop/categories"),

  getCart: () =>
    api.get("/shop/cart"),

  addToCart: (productId: string, quantity: number) =>
    api.post("/shop/cart/items", { product_id: productId, quantity }),

  updateCartItem: (itemId: string, quantity: number) =>
    api.put(`/shop/cart/items/${itemId}`, { quantity }),

  removeFromCart: (itemId: string) =>
    api.delete(`/shop/cart/items/${itemId}`),

  clearCart: () =>
    api.post("/shop/cart/clear"),

  checkout: (data: any) =>
    api.post("/shop/checkout", data),

  getOrders: (page = 1, limit = 20) =>
    api.get("/shop/orders", { params: { page, limit } }),

  getOrderDetails: (id: string) =>
    api.get(`/shop/orders/${id}`),

  cancelOrder: (id: string) =>
    api.post(`/shop/orders/${id}/cancel`),

  trackOrder: (id: string) =>
    api.get(`/shop/orders/${id}/track`),
};
