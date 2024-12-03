import React from 'react';
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

const SidebarMenu = ({ activeSection, setActiveSection }) => {
  const menuItems = [
    { id: 'projects', label: 'Projects' },
    { id: 'teams', label: 'Teams' },
    { id: 'tasks', label: 'Tasks' }
  ];

  return (
    <div className="w-64 border-r bg-background">
      <div className="p-6">
        <h1 className="text-2xl font-bold mb-6">Dashboard</h1>
        <nav>
          <ul className="space-y-2">
            {menuItems.map((item) => (
              <li key={item.id}>
                <Button
                  onClick={() => setActiveSection(item.id)}
                  variant={activeSection === item.id ? "secondary" : "ghost"}
                  className={cn(
                    "w-full justify-start",
                    activeSection === item.id && "bg-secondary"
                  )}
                >
                  {item.label}
                </Button>
              </li>
            ))}
          </ul>
        </nav>
      </div>
    </div>
  );
};

export default SidebarMenu;