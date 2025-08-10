.PHONY: up backend-test frontend-test

up:
docker-compose up -d

backend-test:
cd backend && pytest

frontend-test:
cd frontend && npm test
