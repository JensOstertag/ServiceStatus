FROM ubuntu:22.04

ENV DEBIAN_FRONTEND noninteractive
ENV DEBCONF_NONINTERACTIVE_SEEN true

# Install required packages
RUN apt update -y && apt upgrade -y && \
    apt install -y nginx cron software-properties-common

# Install PHP packages
RUN add-apt-repository ppa:ondrej/php && \
    apt-get install -y php8.2-fpm && \
    apt install -y php8.2-mysql php8.2-curl php8.2-gd php8.2-intl php8.2-mbstring composer

# Cleanup
RUN apt autoremove -y

# Copy application files
COPY . /app
COPY ./docker/nginx-config /etc/nginx
COPY ./docker/entrypoint.sh /app

# Complete application setup
RUN chown -R www-data:www-data /app && \
    chmod +x /app/entrypoint.sh && \
    crontab -u www-data /app/project/cronjobs/.crontab && \
    cd /app && composer install --no-dev --no-interaction

EXPOSE 80
ENTRYPOINT ["/app/entrypoint.sh"]
