FROM alpine:3.23

RUN apk add --no-cache gammu gammu-smsd php83 php83-cli php83-json

RUN mkdir -p /var/spool/gammu/inbox /var/spool/gammu/outbox /var/spool/gammu/sent /var/spool/gammu/error /var/log/gammu

COPY gammu-smsdrc /etc/gammu-smsdrc

COPY web/ /app/web/

COPY entrypoint.sh /app/entrypoint.sh

RUN chmod +x /app/entrypoint.sh

EXPOSE 8080

WORKDIR /app

ENTRYPOINT ["/app/entrypoint.sh"]