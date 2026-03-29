FROM php:8.1-cli

ARG IMAGEMAGICK_VERSION=7.1.2-15

# install dependencies for building ImageMagick and PHP extensions
RUN apt update \
        && apt install -y \
            libjpeg-dev \
            libgif-dev \
            libtiff-dev \
            libpng-dev \
            libwebp-dev \
            libavif-dev \
            libheif-dev \
            libraqm-dev \
            libopenjp2-7-dev \
            liblcms2-dev \
            git \
            zip \
            curl \
            xz-utils \
        && apt-get clean

# build and install ImageMagick from source
RUN curl -o /tmp/ImageMagick.tar.xz -sL \
        "https://imagemagick.org/archive/releases/ImageMagick-${IMAGEMAGICK_VERSION}.tar.xz" \
        && cd /tmp \
        && tar xf ImageMagick.tar.xz \
        && cd "ImageMagick-${IMAGEMAGICK_VERSION}" \
        && ./configure \
        && make -j$(nproc) \
        && make install \
        && ldconfig \
        && cd / \
        && rm -rf /tmp/ImageMagick*

# install PHP extensions
RUN pecl install imagick \
        && pecl install xdebug \
        && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
        && docker-php-ext-enable \
            imagick \
            xdebug \
        && docker-php-ext-install \
            gd \
            exif

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# setup entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
