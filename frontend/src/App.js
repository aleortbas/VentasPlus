import "./App.css";
import { BrowserRouter, Routes, Route } from "react-router";

import Index from "./app/routes/index/index.tsx";
import Dashboard from "./app/routes/dashboard/dashboard.tsx";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Index/>} />
          <Route path="/dashboard" element={<Dashboard/>} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
