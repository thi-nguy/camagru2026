FROM php:8.3-fpm
RUN docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get install -y msmtp msmtp-mta
COPY msmtp.ini /usr/local/etc/php/conf.d/msmtp.ini
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]