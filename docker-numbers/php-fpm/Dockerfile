FROM php:5.6-fpm

COPY conf.d/php.ini $PHP_INI_DIR/conf.d/

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo
RUN docker-php-ext-install mysqli

CMD ["php-fpm"]

EXPOSE 9000
