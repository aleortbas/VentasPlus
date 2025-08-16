import "./App.css";
import { BrowserRouter, Routes, Route } from "react-router";

import Index from "./app/routes/index/index.tsx";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Index/>} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
