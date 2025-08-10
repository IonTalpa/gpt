from fastapi import APIRouter, Depends, HTTPException
from fastapi.security import OAuth2PasswordRequestForm
from sqlmodel import Session, select
from .. import models, schemas
from ..db import get_session
from ..utils.security import (
    verify_password,
    get_password_hash,
    create_access_token,
    create_refresh_token,
    decode_token,
)

router = APIRouter(prefix="/auth", tags=["auth"])


@router.post("/register", response_model=schemas.UserRead)
def register(user_in: schemas.UserCreate, session: Session = Depends(get_session)):
    user = session.exec(
        select(models.User).where(models.User.email == user_in.email)
    ).first()
    if user:
        raise HTTPException(status_code=400, detail="User already exists")
    user = models.User(
        email=user_in.email, hashed_password=get_password_hash(user_in.password)
    )
    session.add(user)
    session.commit()
    session.refresh(user)
    return user


@router.post("/login", response_model=schemas.Token)
def login(
    form_data: OAuth2PasswordRequestForm = Depends(),
    session: Session = Depends(get_session),
):
    user = session.exec(
        select(models.User).where(models.User.email == form_data.username)
    ).first()
    if not user or not verify_password(form_data.password, user.hashed_password):
        raise HTTPException(status_code=400, detail="Incorrect email or password")
    access = create_access_token(user.email)
    refresh = create_refresh_token(user.email)
    return schemas.Token(access_token=access, refresh_token=refresh)


@router.post("/refresh", response_model=schemas.Token)
def refresh(token: schemas.Token):
    email = decode_token(token.refresh_token, refresh=True)
    if not email:
        raise HTTPException(status_code=401, detail="Invalid refresh token")
    access = create_access_token(email)
    refresh_token = create_refresh_token(email)
    return schemas.Token(access_token=access, refresh_token=refresh_token)
