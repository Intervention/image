FROM php:8.4-cli-alpine

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

# libvips is loaded at runtime through FFI by jcupitt/vips
RUN apk add --no-cache vips font-dejavu git unzip \
    && install-php-extensions gd imagick ffi exif opcache xhprof

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-bench.ini
ENV FONT_PATH=/usr/share/fonts/dejavu/DejaVuSans.ttf
