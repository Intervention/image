<?php

declare(strict_types=1);

/**
 * Generate deterministic-ish, photo-like fixtures with Imagick (plasma fractal
 * gives realistic entropy for codecs, unlike flat synthetic images).
 */

$dir = __DIR__ . '/../fixtures';
@mkdir($dir, 0777, true);

function plasma(int $w, int $h): Imagick
{
    $im = new Imagick();
    $im->setOption('seed', '42');
    $im->newPseudoImage($w, $h, 'plasma:fractal');
    $im->blurImage(0, 1.2); // plasma is noisier than a real photo
    $im->setImageColorspace(Imagick::COLORSPACE_SRGB);
    $im->setImageDepth(8);

    return $im;
}

$targets = [
    'photo-4000x3000.jpg' => function (string $path) {
        $im = plasma(4000, 3000);
        $im->setImageFormat('jpeg');
        $im->setImageCompressionQuality(90);
        $im->writeImage($path);
    },
    'photo-1920x1280.jpg' => function (string $path) {
        $im = plasma(1920, 1280);
        $im->setImageFormat('jpeg');
        $im->setImageCompressionQuality(90);
        $im->writeImage($path);
    },
    'photo-1920x1280.webp' => function (string $path) {
        $im = plasma(1920, 1280);
        $im->setImageFormat('webp');
        $im->setImageCompressionQuality(85);
        $im->writeImage($path);
    },
    'alpha-1920x1080.png' => function (string $path) {
        $im = plasma(1920, 1080);
        $mask = new Imagick();
        $mask->newPseudoImage(1920, 1080, 'radial-gradient:white-black');
        $im->compositeImage($mask, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
        $im->setImageFormat('png');
        $im->writeImage($path);
    },
    'watermark-300x100.png' => function (string $path) {
        $im = new Imagick();
        $im->newImage(300, 100, new ImagickPixel('rgba(0,0,0,0)'));
        $draw = new ImagickDraw();
        $draw->setFillColor(new ImagickPixel('rgba(255,255,255,0.6)'));
        $draw->roundRectangle(5, 5, 295, 95, 20, 20);
        $im->drawImage($draw);
        $im->setImageFormat('png');
        $im->writeImage($path);
    },
    'anim-320x240.gif' => function (string $path) {
        $base = plasma(320, 240);
        $anim = new Imagick();
        for ($i = 0; $i < 24; $i++) {
            $frame = clone $base;
            $frame->rollImage($i * 13, $i * 7);
            $frame->quantizeImage(128, Imagick::COLORSPACE_SRGB, 0, false, false);
            $frame->setImageDelay(4);
            $frame->setImageFormat('gif');
            $anim->addImage($frame);
        }
        $anim->setImageIterations(0);
        $anim = $anim->deconstructImages();
        $anim->writeImages($path, true);
    },
    'tiny-16x16.png' => function (string $path) {
        $im = plasma(16, 16);
        $im->setImageFormat('png');
        $im->writeImage($path);
    },
];

foreach ($targets as $name => $build) {
    $path = $dir . '/' . $name;
    if (is_file($path)) {
        continue;
    }
    $build($path);
    printf("generated %s (%s bytes)\n", $name, number_format(filesize($path)));
}
