FROM php:8.2-fpm-alpine

ENV TZ=Pacific/Auckland
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN printf '[PHP]ndate.timezone = "Pacific/Auckland"n' > /usr/local/etc/php/conf.d/tzone.ini

RUN mkdir -p /var/www/api

WORKDIR /var/www/api

RUN docker-php-ext-install pdo pdo_mysql

# RUN mkdir -p /usr/src/php/ext/redis \
#     && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
#     && echo 'redis' >> /usr/src/php-available-exts \
#     && docker-php-ext-install redis

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
