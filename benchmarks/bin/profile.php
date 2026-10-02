<?php

declare(strict_types=1);

/**
 * Profile a snippet with xhprof and print the hottest functions by exclusive
 * wall time (where time is actually spent) and the number of calls.
 *
 *   php bin/profile.php <driver> <snippet-name> [iterations] [top]
 */

use Intervention\Image\Format;
use Intervention\Image\ImageManager;

require __DIR__ . '/../vendor/autoload.php';

[$_, $driver, $snippet] = $argv + [null, 'gd', 'tiny-decode'];
$iterations = (int) ($argv[3] ?? 200);
$top = (int) ($argv[4] ?? 25);
$fx = fn(string $name): string => __DIR__ . '/../fixtures/' . $name;

$manager = ImageManager::usingDriver([
    'gd' => Intervention\Image\Drivers\Gd\Driver::class,
    'imagick' => Intervention\Image\Drivers\Imagick\Driver::class,
    'vips' => Intervention\Image\Drivers\Vips\Driver::class,
][$driver]);

$snippets = [
    'tiny-decode' => fn() => $manager->decodePath($fx('tiny-16x16.png'))->width(),
    'tiny-modify' => fn() => $manager->decodePath($fx('tiny-16x16.png'))
        ->flip()->brightness(1)->contrast(1)->invert()->rotate(90)->resize(16, 16)->crop(16, 16)
        ->encodeUsingFormat(Format::PNG)->toString(),
    'create' => fn() => $manager->createImage(100, 100),
    'color' => fn() => $manager->driver->decodeColor('rgba(0, 0, 255, 0.5)'),
    'colorat' => (function () use ($manager, $fx) {
        $img = $manager->decodePath($fx('tiny-16x16.png'));

        return fn() => $img->colorAt(3, 4);
    })(),
    'thumb' => fn() => $manager->decodePath($fx('photo-1920x1280.jpg'))->scale(width: 320)
        ->encodeUsingFormat(Format::JPEG, quality: 80)->toString(),
    'webp' => fn() => $manager->decodePath($fx('photo-1920x1280.webp'))->scale(width: 640)
        ->encodeUsingFormat(Format::WEBP, quality: 80)->toString(),
    'png' => fn() => $manager->decodePath($fx('alpha-1920x1080.png'))->resize(960, 540)
        ->encodeUsingFormat(Format::PNG)->toString(),
    'watermark' => fn() => $manager->decodePath($fx('photo-1920x1280.jpg'))
        ->insert($fx('watermark-300x100.png'), 20, 20, Intervention\Image\Alignment::BOTTOM_RIGHT)
        ->encodeUsingFormat(Format::JPEG, quality: 80)->toString(),
    'gif' => fn() => $manager->decodePath($fx('anim-320x240.gif'))->scale(width: 160)
        ->encodeUsingFormat(Format::GIF)->toString(),
    'canvas' => fn() => $manager->createImage(800, 600)->fill('#f4f4f4')
        ->drawRectangle(fn($r) => $r->at(10, 10)->size(120, 80)->background('rgba(255, 0, 0, 0.5)')->border('#333', 2))
        ->encodeUsingFormat(Format::PNG)->toString(),
];

$fn = $snippets[$snippet];
$fn();
$fn();

xhprof_enable(XHPROF_FLAGS_NO_BUILTINS * 0); // include builtins: we want to see finfo_open & co.
$t = hrtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $fn();
}
$total = (hrtime(true) - $t) / 1e6;
$data = xhprof_disable();

// aggregate exclusive time per function
$incl = [];
$calls = [];
foreach ($data as $edge => $m) {
    $parts = explode('==>', $edge);
    $callee = end($parts);
    $incl[$callee] = ($incl[$callee] ?? 0) + $m['wt'];
    $calls[$callee] = ($calls[$callee] ?? 0) + $m['ct'];
}
$excl = $incl;
foreach ($data as $edge => $m) {
    if (str_contains($edge, '==>')) {
        [$caller] = explode('==>', $edge);
        $excl[$caller] = ($excl[$caller] ?? 0) - $m['wt'];
    }
}
unset($excl['main()']);
arsort($excl);

printf("%s / %s: %.3f ms per iteration (%d iterations, profiler overhead included)\n\n", $driver, $snippet, $total / $iterations, $iterations);
printf("%-90s %10s %8s %8s\n", 'function (exclusive)', 'excl µs/it', 'incl %', 'calls/it');
$root = $incl['main()'] ?? array_sum($excl);
foreach (array_slice($excl, 0, $top, true) as $fnName => $us) {
    printf(
        "%-90s %10.1f %7.1f%% %8.1f\n",
        substr($fnName, -90),
        $us / $iterations,
        100 * ($incl[$fnName] ?? 0) / max(1, $root),
        $calls[$fnName] / $iterations,
    );
}
