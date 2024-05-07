FROM alpine:latest

# Create webserver user
RUN set -x && \
    adduser -u 1000 -S www-data -G www-data

# Update package manager
RUN apk update && apk upgrade
RUN apk --no-cache add tzdata

# Install nginx and PHP
RUN apk --no-cache add nginx php php-fpm composer

# Install PHP packages
RUN apk --no-cache add php-session php-tokenizer php-mysqli php-pdo php-pdo_mysql php-curl php-gd php-intl php-mbstring php-xml

# Copy application files
COPY --chown=www-data:www-data . /app
COPY ./docker/nginx-config /etc/nginx
COPY ./docker/php-fpm-config /etc/php82/php-fpm.d
COPY ./docker/entrypoint.sh /app

# Adjust permissions
RUN mkdir -p /app/logs && \
    chown -R www-data:www-data /app/logs && \
    chmod +x /app/entrypoint.sh

# Setup crontab
RUN crontab -u www-data /app/project/cronjobs/.crontab

# Install composer dependencies
RUN cd /app && composer install --no-dev --no-interaction

EXPOSE 80
ENTRYPOINT ["/app/entrypoint.sh"]
