from datetime import datetime, timedelta
from typing import Optional
from jose import jwt
from passlib.context import CryptContext
from ..config import get_settings

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
settings = get_settings()


def verify_password(plain_password: str, hashed_password: str) -> bool:
    return pwd_context.verify(plain_password, hashed_password)


def get_password_hash(password: str) -> str:
    return pwd_context.hash(password)


def create_token(subject: str, expires_delta: timedelta, secret_key: str) -> str:
    expire = datetime.utcnow() + expires_delta
    to_encode = {"sub": subject, "exp": expire}
    return jwt.encode(to_encode, secret_key, algorithm="HS256")


def create_access_token(subject: str) -> str:
    delta = timedelta(minutes=settings.access_token_expire_minutes)
    return create_token(subject, delta, settings.secret_key)


def create_refresh_token(subject: str) -> str:
    delta = timedelta(minutes=settings.refresh_token_expire_minutes)
    return create_token(subject, delta, settings.refresh_secret_key)


def decode_token(token: str, refresh: bool = False) -> Optional[str]:
    secret = settings.refresh_secret_key if refresh else settings.secret_key
    try:
        payload = jwt.decode(token, secret, algorithms=["HS256"])
        return payload.get("sub")
    except Exception:
        return None
