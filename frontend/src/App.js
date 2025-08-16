import "./App.css";
import { BrowserRouter, Routes, Route } from "react-router";

import Index from "./app/routes/index/index.tsx";
import Dashboard from "./app/routes/dashboard/dashboard.tsx";
import Comisiones from "./app/routes/comisiones/comision.tsx";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Index/>} />
          <Route path="/dashboard" element={<Dashboard/>} />
          <Route path="/comision" element={<Comisiones/>} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
