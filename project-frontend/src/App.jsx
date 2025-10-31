import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import RegisterSubscribe from "./pages/RegisterSubscribe";
import Dashboard from "./pages/Dashboard";
import "./styles/globals.css";

export default function App() {
  return (
      <BrowserRouter>
        <Routes>
            <Route path="/" element={<RegisterSubscribe />} />
            <Route path="/login" element={<Login />} />
            <Route path="/dashboard" element={<Dashboard />} />
        </Routes>
      </BrowserRouter>
  );
}
