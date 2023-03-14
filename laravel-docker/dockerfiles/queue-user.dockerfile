FROM php:8.2-fpm-alpine

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

CMD ["php", "artisan", "queue:work","--queue=user_queue","--tries=5", "--sleep=10"]
