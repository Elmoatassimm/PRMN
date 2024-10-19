import React from "react";
import Routes from "./routes/routes";
import { useTranslation } from "react-i18next";
import { ThemeProvider } from "./provider/theme-provider";

const App = () => {
  const { i18n } = useTranslation(); // Get i18n instance
  const isRTL = i18n.language === "ar"; // Check if the current language is Arabic

  return (
    
    <div className={`${isRTL ? "font-arabic" : "font-poppins"} font-medium`} dir={isRTL ? "rtl" : "ltr"}> {/* Set direction based on language */}
      <ThemeProvider>
        <Routes />
      </ThemeProvider>
    </div>
    
  );
};

export default App;
