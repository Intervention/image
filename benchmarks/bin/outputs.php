<?php
// Print md5 of encoded outputs to verify optimizations don't change results.
use Intervention\Image\Alignment;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;
require __DIR__ . '/../vendor/autoload.php';
$fx = fn(string $n): string => __DIR__ . '/../fixtures/' . $n;
foreach (['gd' => Intervention\Image\Drivers\Gd\Driver::class, 'imagick' => Intervention\Image\Drivers\Imagick\Driver::class] as $id => $class) {
    $m = ImageManager::usingDriver($class);
    $out = [
        'png-resize' => $m->decodePath($fx('alpha-1920x1080.png'))->resize(960, 540)->encodeUsingFormat(Format::PNG),
        'jpg-watermark' => $m->decodePath($fx('photo-1920x1280.jpg'))->insert($fx('watermark-300x100.png'), 20, 20, Alignment::BOTTOM_RIGHT)->encodeUsingFormat(Format::JPEG, quality: 80),
        'webp-scale' => $m->decodePath($fx('photo-1920x1280.webp'))->scale(width: 640)->encodeUsingFormat(Format::WEBP, quality: 80),
        'gif-scale' => $m->decodePath($fx('anim-320x240.gif'))->scale(width: 160)->encodeUsingFormat(Format::GIF),
        'canvas' => $m->createImage(300, 200)->drawRectangle(fn($r) => $r->at(10, 10)->size(120, 80)->background('rgba(255, 0, 0, 0.5)'))->rotate(30)->encodeUsingFormat(Format::PNG),
        'png-alpha-jpg' => $m->decodePath($fx('alpha-1920x1080.png'))->resizeCanvas(2000, 1200)->encodeUsingFormat(Format::JPEG),
        'mime' => $m->decodePath($fx('photo-1920x1280.webp'))->origin()->mediaType() . ' ' . $m->decodePath($fx('tiny-16x16.png'))->origin()->mediaType(),
    ];
    foreach ($out as $k => $v) {
        printf("%-8s %-14s %s\n", $id, $k, is_string($v) ? $v : md5((string) $v));
    }
}
