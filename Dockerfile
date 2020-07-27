FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

RUN addgroup -g 1000 -S www

RUN adduser -u 1000 -S www -G www

COPY --chown=www:www . /var/www

USER www

COPY --chown=www:www ./.env-docker.example /var/www/.env

# COPY ./composer.json /var/www/

# COPY ./composer.lock /var/www/

RUN /usr/local/bin/composer install

RUN php artisan key:generate

# RUN php artisan migrate




