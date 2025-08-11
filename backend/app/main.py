from fastapi import FastAPI
from sqlmodel import Session as SQLSession, select
from .db import init_db, engine
from . import models
from .config import get_settings
from .utils.security import get_password_hash
from .routes import auth, profiles, imports, baselines, prices

app = FastAPI(title="Fiyat Kontrol API")


@app.on_event("startup")
def on_startup() -> None:
    init_db()
    settings = get_settings()
    with SQLSession(engine) as session:
        admin = session.exec(
            select(models.User).where(models.User.email == "admin@example.com")
        ).first()
        if not admin:
            admin = models.User(
                email="admin@example.com",
                hashed_password=get_password_hash("admin123"),
                is_superuser=True,
            )
            session.add(admin)
            session.commit()


@app.get("/health")
def health() -> dict:
    return {"status": "ok"}


api = FastAPI()
api.include_router(auth.router)
api.include_router(profiles.router)
api.include_router(imports.router)
api.include_router(baselines.router)
api.include_router(prices.router)
app.mount("/api", api)
