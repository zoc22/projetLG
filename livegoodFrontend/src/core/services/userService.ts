import api from "../api/client";

export const userService = {
  getProfile: () =>
    api.get("/profile/me"),

  updateProfile: (data: any) =>
    api.put("/profile/me", data),

  updatePassword: (currentPassword: string, newPassword: string) =>
    api.post("/profile/change-password", { current_password: currentPassword, new_password: newPassword }),

  uploadAvatar: (file: File) => {
    const formData = new FormData();
    formData.append("avatar", file);
    return api.post("/profile/avatar", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });
  },

  getSettings: () =>
    api.get("/profile/settings"),

  updateSettings: (data: any) =>
    api.put("/profile/settings", data),

  getNotificationPreferences: () =>
    api.get("/profile/notification-preferences"),

  updateNotificationPreferences: (data: any) =>
    api.put("/profile/notification-preferences", data),

  getSponsor: () =>
    api.get("/profile/sponsor"),

  getEnroller: () =>
    api.get("/profile/enroller"),

  deleteAccount: () =>
    api.post("/profile/delete-account"),
};
