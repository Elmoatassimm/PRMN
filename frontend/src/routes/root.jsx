// src/components/Header.js
import React from 'react';
import { Link } from 'react-router-dom';
import { Outlet } from 'react-router-dom';

const Root = () => {
  return (
    <header className="bg-blue-500 text-white p-4">
      <nav>
        <ul className="flex space-x-4">
          
          <li>
            <Link to={`login`}>Login</Link>
          </li>
          <li>
            <Link to={`register`}>register</Link>
          </li>
          
        </ul>
      </nav>
      <Outlet />
      <nav>
        <ul className="flex space-x-4">
          
          <li>
            <Link to={`login`}>Login</Link>
          </li>
          <li>
            <Link to={`register`}>register</Link>
          </li>
          
        </ul>
      </nav>
    </header>
  );
};

export default Root;
