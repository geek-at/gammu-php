# gammu-php

## Architecture
- PHP REST API serving from `web/` directory via `php -S`
  - `web/send.php` — sends SMS via `gammu-smsd-inject` (GET, POST, or JSON body; requires `phone` + `text`)
  - `web/get.php` — reads inbox files from `/var/spool/gammu/inbox`, returns JSON array of messages
- `data/sms.log` — append-only log of outgoing SMS commands
- Dockerized with Alpine 3.23 + gammu + gammu-smsd + PHP

## Running

### Docker (recommended)
```
docker compose up -d
```
Requires USB modem attached to `/dev/ttyUSB0` inside the container.

### Native
```
php -S 0.0.0.0:8080 -t web
```

## Prerequisites (native)
- USB modem dongle in modem mode
- `gammu` and `gammu-smsd` installed and configured (`/etc/gammu-smsdrc`)
- PHP with JSON extension

## Docker Files
- `Dockerfile` — Alpine 3.23 with gammu, gammu-smsd, php83
- `gammu-smsdrc` — gammu-smsd configuration (device: `/dev/ttyUSB0`, connection: `at`)
- `entrypoint.sh` — starts gammu-smsd in background, then PHP built-in server
- `docker-compose.yml` — attaches USB device and named volumes for inbox/outbox/logs/sent/error

## Gotchas
- `get.php` uses `Europe/Vienna` timezone (hardcoded at line 5)
- `get.php` reads from `/var/spool/gammu/inbox` — must match the gammu config
- MMS files (`.bin`) are silently skipped by `get.php`
- Multi-part messages are assembled by `findParts()` in `get.php:73` — only the first part (`part==0`) triggers assembly
- `send.php` sanitizes phone numbers with `FILTER_SANITIZE_NUMBER_INT` — no validation beyond that
- When running in Docker, the USB modem device path inside container must match `gammu-smsdrc` (default: `/dev/ttyUSB0`)