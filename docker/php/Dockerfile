FROM composer:2 as composer

FROM php:8.3-fpm

RUN apt-get update && \
    apt-get install -y wget openssl unzip

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug-3.3.2 && \
    docker-php-ext-enable xdebug opcache

RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mkdir /.composer && \
    chmod 777 -R /.composer

COPY ./xdebug.ini /usr/local/etc/php/conf.d

RUN mkdir -p /var/www/html/var && \
    chown -R 1000:1000 /var/www/html/var && \
    chmod -R ug+rwX /var/www/html/var

RUN echo "alias composer='XDEBUG_MODE=off \\\composer'" >> "$HOME/.bashrc"

WORKDIR /var/www/html
