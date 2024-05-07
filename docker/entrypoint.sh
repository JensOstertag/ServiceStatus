#!/bin/sh

env > /etc/environment

php-fpm82
nginx
crond

tail -f /dev/null
