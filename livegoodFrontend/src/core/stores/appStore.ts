import { reactive } from "vue";

interface AppState {
  lang: string;
  autoTranslate: boolean;
}

const storedPrefs = localStorage.getItem("livegood-preferences");
const initialPrefs = storedPrefs ? JSON.parse(storedPrefs) : { lang: "fr", autoTranslate: true };

const state = reactive<AppState & {
  setLang: (lang: string) => void;
  toggleAutoTranslate: () => void;
}>({
  lang: initialPrefs.lang || "fr",
  autoTranslate: initialPrefs.autoTranslate !== undefined ? initialPrefs.autoTranslate : true,

  setLang(lang: string) {
    this.lang = lang;
    this.save();
  },

  toggleAutoTranslate() {
    this.autoTranslate = !this.autoTranslate;
    this.save();
  },

  save() {
    localStorage.setItem("livegood-preferences", JSON.stringify({
      lang: this.lang,
      autoTranslate: this.autoTranslate
    }));
  }
});

export const useAppStore = () => state;
