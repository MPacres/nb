FROM php:8.0-fpm

# Install AMQP extension
RUN apt-get update && apt-get install -y \
    librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

FROM php:8.2-fpm-alpine3.18

COPY --from=ext-amqp /usr/local/etc/php/conf.d/docker-php-ext-amqp.ini /usr/local/etc/php/conf.d/docker-php-ext-amqp.ini
COPY --from=ext-amqp /usr/local/lib/php/extensions/no-debug-non-zts-20220829/amqp.so /usr/local/lib/php/extensions/no-debug-non-zts-20220829/amqp.so

FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev \
    libzip-dev default-mysql-client \
    nano

RUN a2enmod rewrite

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

# Install additional PHP extensions
RUN docker-php-ext-install pdo_mysql sockets

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY . /var/www

# Install PHP dependencies using Composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader

EXPOSE 80

RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Install Supervisor
RUN apt-get update && apt-get install -y supervisor

# Copy Supervisor configuration file
COPY supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set default command to run Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
