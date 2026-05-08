FROM php:5.6-apache

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY entrypoint.sh /entrypoint.sh
COPY init-db.php /init-db.php
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html
COPY . /var/www/html
RUN rm -f /var/www/html/entrypoint.sh /var/www/html/init-db.php

ENTRYPOINT ["/entrypoint.sh"]
