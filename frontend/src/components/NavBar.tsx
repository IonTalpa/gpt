import { Link } from "react-router-dom";
import { useTranslation } from "react-i18next";

export default function NavBar() {
  const { i18n } = useTranslation();
  const changeLang = (lng: string) => i18n.changeLanguage(lng);
  return (
    <nav className="mb-4 flex gap-4">
      <Link to="/">Home</Link>
      <Link to="/imports">Imports</Link>
      <Link to="/baselines">Baselines</Link>
      <Link to="/prices">Prices</Link>
      <button onClick={() => changeLang("tr")}>TR</button>
      <button onClick={() => changeLang("en")}>EN</button>
    </nav>
  );
}
