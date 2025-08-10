import { Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import Imports from "./pages/Imports";
import ImportMap from "./pages/ImportMap";
import ImportValidate from "./pages/ImportValidate";
import ImportDiff from "./pages/ImportDiff";
import Baselines from "./pages/Baselines";
import Prices from "./pages/Prices";
import NavBar from "./components/NavBar";

export default function App() {
  return (
    <div className="p-4">
      <NavBar />
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/imports" element={<Imports />} />
        <Route path="/imports/:id/map" element={<ImportMap />} />
        <Route path="/imports/:id/validate" element={<ImportValidate />} />
        <Route path="/imports/:id/diff" element={<ImportDiff />} />
        <Route path="/baselines" element={<Baselines />} />
        <Route path="/prices" element={<Prices />} />
        <Route path="/" element={<div>Home</div>} />
      </Routes>
    </div>
  );
}
