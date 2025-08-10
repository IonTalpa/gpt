# AI Agent Bridge

WordPress plugin exposing secure REST endpoints for external AI systems.

## Installation
1. Copy `ai-agent-bridge` to `wp-content/plugins/`.
2. Activate via **Plugins**.
3. Go to **Settings → AI Agent** and set the shared secret and optional IP allowlist.

## Settings
- **Shared Secret**: HS256 key for JWT.
- **IP Allowlist**: comma separated list.
- **Test Ping**: sends signed `/ping` request to verify setup.

## Example Requests
Replace `<TOKEN>` with a valid JWT signed using the shared secret.

```
# create post
timestamp=$(date +%s)
nonce=$(uuidgen)
idem=$(uuidgen)
curl -X POST https://example.com/wp-json/ai-agent/v1/posts \
 -H "Authorization: Bearer <TOKEN>" \
 -H "X-AI-Timestamp: $timestamp" \
 -H "X-AI-Nonce: $nonce" \
 -H "Idempotency-Key: $idem" \
 -d '{"title":"Hi","content":"Hello"}'
```

## Error Codes
| Code | Meaning |
|------|---------|
| `forbidden` | Auth, IP, timestamp or nonce invalid |
| `rate_limited` | Too many requests |
| `conflict` | Idempotency key reused |
| `invalid` | Missing or bad input |

## License
GPL-2.0-or-later
