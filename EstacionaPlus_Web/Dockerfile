FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    openssl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && apt-get clean

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN a2enmod ssl rewrite headers

RUN mkdir -p /etc/apache2/ssl

COPY ./ssl/certificate.crt /etc/apache2/ssl/
COPY ./ssl/private.key /etc/apache2/ssl/

COPY . /var/www/html/

COPY ./config/000-default.conf /etc/apache2/sites-available/
COPY ./config/default-ssl.conf /etc/apache2/sites-available/

RUN a2ensite default-ssl.conf

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer install --no-dev --optimize-autoloader

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
