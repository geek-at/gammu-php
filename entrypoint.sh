#!/bin/sh
set -e

mkdir -p /var/spool/gammu/inbox /var/spool/gammu/outbox /var/spool/gammu/sent /var/spool/gammu/error /var/log/gammu

echo "Starting gammu-smsd..."
gammu-smsd -c /etc/gammu-smsdrc &
GSM_PID=$!

sleep 2

if ! kill -0 $GSM_PID 2>/dev/null; then
    echo "gammu-smsd failed to start. Check /var/log/gammu/smsd.log"
    exit 1
fi

echo "Starting PHP built-in server on 0.0.0.0:8080..."
php -S 0.0.0.0:8080 -t /app/web