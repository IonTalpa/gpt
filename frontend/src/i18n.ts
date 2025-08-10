import i18n from "i18next";
import { initReactI18next } from "react-i18next";

const resources = {
  en: {
    translation: {
      welcome: "Welcome",
      login: "Login",
    },
  },
  tr: {
    translation: {
      welcome: "Hoşgeldiniz",
      login: "Giriş",
    },
  },
};

i18n.use(initReactI18next).init({
  resources,
  lng: "tr",
  fallbackLng: "en",
  interpolation: { escapeValue: false },
});

export default i18n;
