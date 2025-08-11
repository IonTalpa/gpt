from __future__ import annotations
from datetime import date, datetime
from typing import Optional
from sqlmodel import SQLModel, Field
from sqlalchemy import Index


class User(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    email: str = Field(index=True, unique=True)
    hashed_password: str
    is_active: bool = Field(default=True)
    is_superuser: bool = Field(default=False)


class ImportProfile(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    name: str
    mapping: str  # JSON string


class ImportJob(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    profile_id: Optional[int] = Field(default=None, foreign_key="importprofile.id")
    created_at: datetime = Field(default_factory=datetime.utcnow)


class BaselineSet(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    name: str
    created_at: datetime = Field(default_factory=datetime.utcnow)


class BaselineItem(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    baseline_set_id: int = Field(foreign_key="baselineset.id")
    hotel_code: str
    room_type: str
    board: str
    start_date: date
    end_date: date
    price: float
    currency: str = "TRY"


class PriceRecord(SQLModel, table=True):
    id: Optional[int] = Field(default=None, primary_key=True)
    hotel_code: str
    room_type: str
    board: str
    start_date: date
    end_date: date
    price: float
    currency: str = "TRY"

    __table_args__ = (
        Index(
            "idx_price_unique",
            "hotel_code",
            "room_type",
            "board",
            "start_date",
            "end_date",
        ),
    )
