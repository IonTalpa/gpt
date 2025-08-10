from pydantic_settings import BaseSettings


class Settings(BaseSettings):
    secret_key: str = "changeme"
    refresh_secret_key: str = "changeme2"
    access_token_expire_minutes: int = 30
    refresh_token_expire_minutes: int = 60 * 24
    database_url: str = "sqlite:///var/data/sqlite/app.db"
    timezone: str = "Europe/Istanbul"
    default_currency: str = "TRY"

    class Config:
        env_file = ".env"
        case_sensitive = False


def get_settings() -> Settings:
    return Settings()
