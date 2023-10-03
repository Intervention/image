FROM php:8-cli

RUN apt update \
        && apt install -y \
            libpng-dev \
            libicu-dev \
            libavif-dev \
            libpq-dev \
            libzip-dev \
            zip \
            zlib1g-dev \
            locales \
            locales-all \
            libmagickwand-dev \
            libwebp-dev \
        && pecl install imagick \
        && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
        && docker-php-ext-enable imagick \
        && docker-php-ext-install \
            intl \
            opcache \
            pdo \
            pdo_pgsql \
            pdo_mysql \
            pgsql \
            fileinfo \
            mysqli \
            gd \
            bcmath \
            exif \
            zip \
        && apt-get clean

# install composer
#
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
