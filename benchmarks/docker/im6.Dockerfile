# ImageMagick 6 (Debian bookworm ships 6.9.11), as covered by the CI matrix
FROM php:8.4-cli-bookworm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends fonts-dejavu-core libvips42 git unzip \
    && install-php-extensions gd imagick ffi exif opcache \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-bench.ini
ENV FONT_PATH=/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf
