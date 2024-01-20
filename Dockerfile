FROM php:8.1-cli

# install dependencies
RUN apt update \
        && apt install -y \
            libmagickwand-dev \
            libwebp-dev \
            libpng-dev \
            libavif-dev \
            git \
            zip \
        && pecl install imagick \
        && pecl install xdebug \
        && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
        && docker-php-ext-enable \
            imagick \
            xdebug \
        && docker-php-ext-install \
            gd \
            exif \
        && apt-get clean

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
