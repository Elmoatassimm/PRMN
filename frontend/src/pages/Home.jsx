import { ArrowDownRight } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTheme } from "../provider/theme-provider"; // Assuming you have a ThemeProvider

import { useTranslation } from 'react-i18next'; // Import translation hook

const Home = () => {
  const { t } = useTranslation();
  const { i18n } = useTranslation(); // Get i18n instance
  const isRTL = i18n.language === "ar";
  const { theme } = useTheme();
  return (
    <section className="py-10 lg:mx-32 min-[600px]:mx-1">
      <div className="container">
        <div className="grid items-center gap-8 lg:grid-cols-2">
        <div className={`flex flex-col items-center text-center lg:items-start ${isRTL ? 'lg:text-right' : 'lg:text-left'}`}>

            <Badge variant="outline">
            {t('home.new_feature')}
              <ArrowDownRight className="ml-2 size-4" />
            </Badge>
            <h1 className="my-6 text-pretty text-4xl font-bold lg:text-6xl" >
            {t('home.title')}
            </h1>
            <p className="mb-8 max-w-xl text-muted-foreground lg:text-xl">
            {t('home.description')}
            </p>
            <div className="flex w-full flex-col justify-center gap-2 sm:flex-row lg:justify-start">
              <Button className="w-full sm:w-auto">{t('home.get_started')}</Button>
              <Button variant="outline" className="w-full sm:w-auto">
              {t('home.learn_more')}
                <ArrowDownRight className="ml-2 size-4" />
              </Button>
            </div>
          </div>
          <img
            src={theme === 'dark' ? "../../public/project-management-dark.png" : "../../public/project-management.png"}
            alt="Project management illustration"
            className=" w-64 mx-40 rounded-md object-cover"
          />
        </div>
      </div>
    </section>
  );
};

export default Home;
