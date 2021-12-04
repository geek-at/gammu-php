# gammu-php

A php API that enables you to send and receive SMS via a simple REST API

Find more about how to use it here:

https://blog.haschek.at/2021/raspberry-pi-sms-gateway.html
===

# Prerequisites
- A USB modem dongle you can put into modem mode
- gammu and gammu-smsd
- php and php-json

From within the directory where the two php files (`send.php` and `get.php`) are stored, run `php -S 0.0.0.0:8080` which will serve the two files to anyone on the network on port 8080.

## Sending SMS

Just call `http://ip.of.your.pi/send.php?phone=07921XXXXXX&text=Testmessage` from curl or a browser and it will return a JSON object indicating if it failed (status:error), or succeeded (status:ok)

```json
{
  "status": "ok",
  "log": "2021-12-04 15:43:39\ngammu-smsd-inject TEXT 07921XXXXXX -unicode -text 'Testmessage'\ngammu-smsd-inject[2669]: Warning: No PIN code in /etc/gammu-smsdrc file\ngammu-smsd-inject[2669]: Created outbox message OUTC20211204_164340_00_07921XXXXXX_sms0.smsbackup\nWritten message with ID /var/spool/gammu/outbox/OUTC20211204_164340_00_07921XXXXXX_sms0.smsbackup\n\n\n"
}
```

### Receiving SMS with the API

Call `http://ip.of.your.pi:8080/get.php`

And it will return you all messages also in a JSON object

```json
curl -s http://ip.of.your.pi:8080/get.php
[
  {
    "id": "f0a7789a657bb34e43c17c8e64609c48",
    "timestamp": 1638636342,
    "year": "2021",
    "month": "12",
    "day": "04",
    "time": "16:45",
    "test": "04.12.2021 16:45:42",
    "sender": "+437921XXXXXX",
    "message": "Hello bob!"
  },
  {
    "id": "c358d0a4e5868c1d7d2eedab181eddd6",
    "timestamp": 1638636414,
    "year": "2021",
    "month": "12",
    "day": "04",
    "time": "16:46",
    "test": "04.12.2021 16:46:54",
    "sender": "+437921XXXXXX",
    "message": "Hello "
  }
]
```