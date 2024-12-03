import React, { useState } from "react";
import { Link } from "react-router-dom";
import Logout from "./Logout";
import { useAuth } from "../provider/authProvider";
import LanguageSwitcher from "./LanguageSwitcher";
import { useTranslation } from "react-i18next";
import { ModeToggle } from "./mode-toggle";
import { Button } from "./ui/button";
import { Menu, X } from "lucide-react"; // Icons for mobile toggle

const Header = () => {
  const { token } = useAuth();
  const { t } = useTranslation();
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false); // State for mobile menu

  const toggleMobileMenu = () => {
    setIsMobileMenuOpen(!isMobileMenuOpen);
  };

  return (
    <header className="bg-background shadow-md">
      <div className="container mx-auto px-4 py-4 flex justify-between items-center">
        {/* Left side: Logo or Home Link */}
        <div>
          <Link to="/" className="text-xl font-semibold hover:text-primary transition-colors">
            {t("header.home")}
          </Link>
        </div>

        {/* Mobile Menu Button */}
        <button
          className="lg:hidden text-muted-foreground focus:outline-none"
          onClick={toggleMobileMenu}
          aria-label="Toggle Menu"
        >
          {isMobileMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
        </button>

        {/* Right side: Links and actions (hidden on mobile, visible on desktop) */}
        <div className="hidden lg:flex items-center space-x-6">
          {token ? (
            <>
              <Link to="/profile" className="text-muted-foreground hover:text-primary">
                {t("header.profile")}
              </Link>
              <Logout /> {/* Logout button */}
            </>
          ) : (
            <>
              <Link to="/login" className="text-muted-foreground  hover:text-primary">
                {t("header.login")}
              </Link>
              <Button asChild variant="link">
                <Link to="/register">{t("header.register")}</Link>
              </Button>
            </>
          )}
          <LanguageSwitcher />
          <ModeToggle />
        </div>

        {/* Mobile Menu: visible when toggled */}
        {isMobileMenuOpen && (
          <div className="lg:hidden absolute top-16 left-0 w-full bg-background shadow-lg z-10">
            <ul className="flex flex-col items-center space-y-4 py-4">
              <li>
                <Link to="/" className="text-muted-foreground hover:text-primary">
                  {t("header.home")}
                </Link>
              </li>
              {token ? (
                <>
                  <li>
                    <Link to="/profile" className="text-muted-foreground hover:text-primary">
                      {t("header.profile")}
                    </Link>
                  </li>
                  <li>
                    <Logout />
                  </li>
                </>
              ) : (
                <>
                  <li>
                    <Link to="/login" className="text-muted-foreground hover:text-primary">
                      {t("header.login")}
                    </Link>
                  </li>
                  <li>
                    <Button asChild variant="link">
                      <Link to="/register">{t("header.register")}</Link>
                    </Button>
                  </li>
                </>
              )}
              <li>
                <LanguageSwitcher />
              </li>
              <li>
                <ModeToggle />
              </li>
            </ul>
          </div>
        )}
      </div>
    </header>
  );
};

export default Header;
