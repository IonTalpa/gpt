from fastapi import APIRouter, Depends
from sqlmodel import Session
from ..deps import get_current_user
from ..db import get_session

router = APIRouter(prefix="/profiles", tags=["profiles"])


@router.get("", dependencies=[Depends(get_current_user)])
def list_profiles(session: Session = Depends(get_session)):
    return []
