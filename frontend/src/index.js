import React from "react";
import ReactDOM from "react-dom/client";
import axios from "axios";

import "./index.css";
import "./app/routes/index/index.tsx";

import App from "./App";
import reportWebVitals from "./reportWebVitals";

const accessToken = localStorage.getItem('accessToken');
axios.defaults.headers.common['Authorization'] = `Bearer ${accessToken}`;


const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

reportWebVitals();