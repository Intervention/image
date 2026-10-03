FROM php:8.4-cli

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

# libvips is loaded at runtime through FFI by jcupitt/vips
RUN apt-get update \
    && apt-get install -y --no-install-recommends libvips42t64 fonts-dejavu-core git unzip \
    && install-php-extensions gd imagick ffi exif opcache xhprof \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-bench.ini
ENV FONT_PATH=/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf
