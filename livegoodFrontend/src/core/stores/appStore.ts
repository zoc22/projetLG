import { create } from "zustand";
import { persist } from "zustand/middleware";

interface AppState {
  lang: string;
  autoTranslate: boolean;
  setLang: (lang: string) => void;
  toggleAutoTranslate: () => void;
}

export const useAppStore = create<AppState>()(
  persist(
    (set) => ({
      lang: "fr",
      autoTranslate: true,
      setLang: (lang) => set({ lang }),
      toggleAutoTranslate: () => set((state) => ({ autoTranslate: !state.autoTranslate })),
    }),
    {
      name: "livegood-preferences",
    }
  )
);
