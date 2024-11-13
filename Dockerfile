FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    curl \
    ffmpeg \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# RUN pecl install xdebug && \
#     docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/we

COPY . /var/www/we

# RUN chown -R www-data:www-data /var/www/we/storage /var/www/we/bootstrap/cache

CMD ["php-fpm"]

EXPOSE 9000
