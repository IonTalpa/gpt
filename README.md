# Fiyat Kontrol Asistanı

FastAPI ve React tabanlı otel fiyat kontrol uygulaması. Docker Compose ile tek komutla ayağa kalkar ve Coolify üzerinden sıfır konfigürasyonla deploy edilebilir.

## Özellikler
- CSV fiyat listesi içe aktarma
- JWT tabanlı kimlik doğrulama
- Basit fiyat listesi ve baseline yönetimi
- TR/EN çok dillilik

## Hızlı Başlangıç

```bash
docker-compose up -d
```

Uygulama ayaklandıktan sonra [http://localhost](http://localhost) adresine gidin.

### Varsayılan Admin Hesabı
- **E-posta:** `admin@example.com`
- **Şifre:** `admin123`

## Coolify ile Deploy
1. Bu repo'yu GitHub'da barındırın.
2. Coolify'da **New Application** oluşturun.
3. Source olarak repo'yu seçin, branch `main`.
4. Deploy butonuna basın.

## Çevre Değişkenleri
`.env.example` dosyasını kopyalayarak `.env` oluşturun.

| Değişken | Açıklama |
|----------|---------|
| `SECRET_KEY` | JWT access secret |
| `REFRESH_SECRET_KEY` | JWT refresh secret |
| `DATABASE_URL` | SQLite yolu |
| `ACCESS_TOKEN_EXPIRE_MINUTES` | Access token süresi |
| `REFRESH_TOKEN_EXPIRE_MINUTES` | Refresh token süresi |
| `TZ` | Zaman dilimi |
| `DEFAULT_CURRENCY` | Varsayılan para birimi |

## Testler
Backend için `pytest`, frontend için `vitest` kullanılmaktadır.

```bash
make backend-test
make frontend-test
```

## Sağlık Kontrolleri
- Backend: `GET /health` → `{status: "ok"}`
- Frontend: `GET /` → HTTP 200
