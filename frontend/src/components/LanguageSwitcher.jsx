import React from "react";
import { useTranslation } from "react-i18next";

const LanguageSelector = () => {
  const { i18n, t } = useTranslation(); // Include the 't' function for translations

  const changeLanguage = (lang) => {
    i18n.changeLanguage(lang);
  };

  return (
    <div className="flex items-center space-x-2">
      <span className="text-sm font-medium text-muted-foreground mx-1">
        {t("select_language")} {/* Use t function to translate "Select Language" */}
      </span>
      <select
        className="bg-background text-foreground border border-muted-foreground rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition-colors"
        value={i18n.language}
        onChange={(e) => changeLanguage(e.target.value)}
      >
        <option value="en">English</option>
        <option value="fr">French</option>
        <option value="ar">Arabic</option>
      </select>
    </div>
  );
};

export default LanguageSelector;
