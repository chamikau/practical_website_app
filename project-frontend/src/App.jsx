import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Auth/Login";
import RegisterSubscribe from "./pages/Auth/RegisterSubscribe";
import DashboardPage from "./pages/Dashboard/DashboardPage";
import "./assets/style.css";

export default function App() {
  return (
      <BrowserRouter>
        <Routes>
            <Route path="/" element={<RegisterSubscribe />} />
            <Route path="/login" element={<Login />} />
            <Route path="/dashboard" element={<DashboardPage />} />
        </Routes>
      </BrowserRouter>
  );
}
