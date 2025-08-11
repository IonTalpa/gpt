from fastapi import APIRouter, Depends
from sqlmodel import Session
from ..deps import get_current_user
from ..db import get_session

router = APIRouter(prefix="/prices", tags=["prices"])


@router.get("", dependencies=[Depends(get_current_user)])
def list_prices(session: Session = Depends(get_session)):
    return []
