#!/bin/bash
set -e

FORCED_USER_ID=${LOCAL_USER_ID:-9001}
echo "Starting cron service with UID: $FORCED_USER_ID"

id kop > /dev/null 2>&1 || useradd --shell /bin/bash --no-create-home --home /home/kop -u $FORCED_USER_ID -o -c "" kop
mkdir -p /home/kop && chown $FORCED_USER_ID /home/kop

# Install crontab for kop user
crontab -u kop /etc/cron.d/kop

exec cron -f -L /dev/stdout
