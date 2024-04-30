#!/usr/bin/env sh

echo "$(hostname -i)\t$(hostname) $(hostname).localhost" >> /etc/hosts
service sendmail start

service php8.2-fpm start
service nginx start
service cron start

tail -f /dev/null
