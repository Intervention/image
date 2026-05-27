FROM php:8.3-cli-alpine

ARG IMAGEMAGICK_VERSION=7.1.2-15

# install dependencies for building ImageMagick and PHP extensions
RUN apk add --no-cache \
        jpeg-dev \
        giflib-dev \
        tiff-dev \
        libpng-dev \
        libwebp-dev \
        libavif-dev \
        libheif-dev \
        harfbuzz-dev \
        openjpeg-dev \
        lcms2-dev \
        freetype-dev \
        git \
        zip \
        curl \
        xz \
        autoconf \
        g++ \
        make

# build and install ImageMagick from source
RUN curl -o /tmp/ImageMagick.tar.xz -sL \
        "https://imagemagick.org/archive/releases/ImageMagick-${IMAGEMAGICK_VERSION}.tar.xz" \
        && cd /tmp \
        && tar xf ImageMagick.tar.xz \
        && cd "ImageMagick-${IMAGEMAGICK_VERSION}" \
        && ./configure \
        && make -j$(nproc) \
        && make install \
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
