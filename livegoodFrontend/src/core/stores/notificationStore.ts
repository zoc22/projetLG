import { reactive } from "vue";

export type NotificationType = "success" | "error" | "info" | "warning";

interface Notification {
  id: number;
  message: string;
  type: NotificationType;
  title?: string;
}

const state = reactive<{
  notifications: Notification[];
  add: (message: string, type?: NotificationType, title?: string) => void;
  remove: (id: number) => void;
  success: (message: string, title?: string) => void;
  error: (message: string, title?: string) => void;
}>({
  notifications: [],

  add(message: string, type: NotificationType = "info", title?: string) {
    const id = Date.now();
    this.notifications.push({ id, message, type, title });
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
      this.remove(id);
    }, 5000);
  },

  remove(id: number) {
    const index = this.notifications.findIndex((n) => n.id === id);
    if (index !== -1) {
      this.notifications.splice(index, 1);
    }
  },

  success(message: string, title: string = "Succès") {
    this.add(message, "success", title);
  },

  error(message: string, title: string = "Erreur") {
    this.add(message, "error", title);
  }
});

export const useNotificationStore = () => state;
