<?php

declare(strict_types=1);

/**
 * Intervention Image benchmark runner.
 *
 *   php bin/bench.php [--drivers=gd,imagick,vips] [--filter=regex] [--min-time=1.5] [--max-iter=200] [--out=file.json]
 *
 * Each scenario is warmed up, then repeated until --min-time seconds (or --max-iter)
 * are reached. The median wall time is reported (robust against VM noise).
 * Scenarios prefixed with "native:" use the raw extension directly, mirroring what
 * the library does internally, to expose the library's own overhead.
 */

use Intervention\Image\Alignment;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageManagerInterface;

require __DIR__ . '/../vendor/autoload.php';

$opts = getopt('', ['drivers::', 'filter::', 'min-time::', 'max-iter::', 'out::', 'profile::']);
$drivers = explode(',', $opts['drivers'] ?? 'gd,imagick,vips');
$filter = $opts['filter'] ?? null;
$minTime = (float) ($opts['min-time'] ?? 1.5);
$maxIter = (int) ($opts['max-iter'] ?? 200);
$fx = fn(string $name): string => __DIR__ . '/../fixtures/' . $name;
$font = getenv('FONT_PATH') ?: throw new RuntimeException('FONT_PATH not set');

$driverClasses = [
    'gd' => Intervention\Image\Drivers\Gd\Driver::class,
    'imagick' => Intervention\Image\Drivers\Imagick\Driver::class,
    'vips' => Intervention\Image\Drivers\Vips\Driver::class,
];

/**
 * Scenarios: name => [closure(ImageManagerInterface $m, string $driver): void, ops per call]
 * "ops" > 1 means the closure loops internally; per-op time is reported too.
 */
$scenarios = [
    // --- real world pipelines -------------------------------------------------
    'thumb: jpg 4000x3000 -> scale 320 -> jpg' => [function (ImageManagerInterface $m) use ($fx) {
        $m->decodePath($fx('photo-4000x3000.jpg'))->scale(width: 320)->encodeUsingFormat(Format::JPEG, quality: 80)->toString();
    }, 1],
    'cover: jpg 1920x1280 -> cover 800x600 -> webp' => [function (ImageManagerInterface $m) use ($fx) {
        $m->decodePath($fx('photo-1920x1280.jpg'))->cover(800, 600)->encodeUsingFormat(Format::WEBP, quality: 80)->toString();
    }, 1],
    'webp: webp 1920x1280 -> scale 640 -> webp' => [function (ImageManagerInterface $m) use ($fx) {
        $m->decodePath($fx('photo-1920x1280.webp'))->scale(width: 640)->encodeUsingFormat(Format::WEBP, quality: 80)->toString();
    }, 1],
    'png: alpha png 1920x1080 -> resize 960x540 -> png' => [function (ImageManagerInterface $m) use ($fx) {
        $m->decodePath($fx('alpha-1920x1080.png'))->resize(960, 540)->encodeUsingFormat(Format::PNG)->toString();
    }, 1],
    'gif: animated 24f 320x240 -> scale 160 -> gif' => [function (ImageManagerInterface $m) use ($fx) {
        $m->decodePath($fx('anim-320x240.gif'))->scale(width: 160)->encodeUsingFormat(Format::GIF)->toString();
    }, 1],
    'watermark: jpg 1920x1280 + png -> jpg' => [function (ImageManagerInterface $m) use ($fx) {
        $m->decodePath($fx('photo-1920x1280.jpg'))
            ->insert($fx('watermark-300x100.png'), 20, 20, Alignment::BOTTOM_RIGHT)
            ->encodeUsingFormat(Format::JPEG, quality: 80)->toString();
    }, 1],
    'filters: jpg 1920x1280 gray+bright+contrast+blur -> jpg' => [function (ImageManagerInterface $m) use ($fx) {
        $m->decodePath($fx('photo-1920x1280.jpg'))
            ->grayscale()->brightness(10)->contrast(10)->blur(2)
            ->encodeUsingFormat(Format::JPEG, quality: 80)->toString();
    }, 1],
    'binary: decodeBinary jpg 1920x1280 -> jpg' => [function (ImageManagerInterface $m) use ($fx) {
        static $data = null;
        $data ??= file_get_contents($fx('photo-1920x1280.jpg'));
        $m->decodeBinary($data)->encodeUsingFormat(Format::JPEG, quality: 80)->toString();
    }, 1],
    'canvas: 800x600 fill + 40 shapes + text -> png' => [function (ImageManagerInterface $m) use ($font) {
        $img = $m->createImage(800, 600)->fill('#f4f4f4');
        for ($i = 0; $i < 20; $i++) {
            $img->drawRectangle(fn($r) => $r->at($i * 30, $i * 20)->size(120, 80)->background('rgba(255, 0, 0, 0.5)')->border('#333', 2));
            $img->drawEllipse(fn($e) => $e->at(400 + $i * 10, 300)->size(100, 60)->background('hsl(200, 80%, 50%)'));
        }
        $img->text('Intervention Image benchmark', 400, 300, fn($f) => $f->filename($font)->size(32)->color('#222')->align('center'));
        $img->encodeUsingFormat(Format::PNG)->toString();
    }, 1],

    // --- library overhead (tiny images -> fixed per-call cost dominates) --------
    'overhead: decodePath tiny png x50' => [function (ImageManagerInterface $m) use ($fx) {
        for ($i = 0; $i < 50; $i++) {
            $m->decodePath($fx('tiny-16x16.png'))->width();
        }
    }, 50],
    'overhead: decode() auto-detect tiny png path x50' => [function (ImageManagerInterface $m) use ($fx) {
        for ($i = 0; $i < 50; $i++) {
            $m->decode($fx('tiny-16x16.png'))->width();
        }
    }, 50],
    'overhead: tiny png -> 10 modifiers -> png x20' => [function (ImageManagerInterface $m) use ($fx) {
        $img = $m->decodePath($fx('tiny-16x16.png'));
        for ($i = 0; $i < 20; $i++) {
            $img->flip()->flip()->brightness(1)->contrast(1)->invert()->invert()->rotate(90)->rotate(-90)->resize(16, 16)->crop(16, 16);
            $img->encodeUsingFormat(Format::PNG)->toString();
        }
    }, 20],
    'overhead: colorAt x1000' => [function (ImageManagerInterface $m) use ($fx) {
        static $img = [];
        $img[$m->driver->id()] ??= $m->decodePath($fx('tiny-16x16.png'));
        $image = $img[$m->driver->id()];
        for ($i = 0; $i < 1000; $i++) {
            $image->colorAt($i % 16, intdiv($i, 16) % 16);
        }
    }, 1000],
    'overhead: decodeColor mixed x1000' => [function (ImageManagerInterface $m) {
        $inputs = ['#ff0000', 'f00', 'rgb(255, 0, 0)', 'rgba(0, 0, 255, 0.5)', 'hsl(200, 80%, 50%)', 'tomato', 'cmyk(0, 100, 100, 0)', 'oklch(0.7 0.1 120)'];
        for ($i = 0; $i < 1000; $i++) {
            $m->driver->decodeColor($inputs[$i % 8]);
        }
    }, 1000],
    'overhead: createImage 100x100 x100' => [function (ImageManagerInterface $m) {
        for ($i = 0; $i < 100; $i++) {
            $m->createImage(100, 100);
        }
    }, 100],
];

// native baselines: same work as the library pipeline, using the raw extension only
$native = [
    'native: thumb jpg 4000x3000 -> scale 320 -> jpg' => [
        'gd' => function () use ($fx) {
            $src = imagecreatefromjpeg($fx('photo-4000x3000.jpg'));
            $dst = imagecreatetruecolor(320, 240);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, 320, 240, 4000, 3000);
            ob_start();
            imagejpeg($dst, null, 80);
            ob_end_clean();
        },
        'imagick' => function () use ($fx) {
            $im = new Imagick($fx('photo-4000x3000.jpg'));
            $im->scaleImage(320, 240);
            $im->setImageCompressionQuality(80);
            $im->setImageFormat('jpeg');
            $im->getImageBlob();
        },
        'vips' => function () use ($fx) {
            Jcupitt\Vips\Image::thumbnail($fx('photo-4000x3000.jpg'), 320, ['height' => 240, 'size' => 'force'])
                ->writeToBuffer('.jpg', ['Q' => 80]);
        },
    ],
    'native: decode tiny png x50' => [
        'gd' => function () use ($fx) {
            for ($i = 0; $i < 50; $i++) {
                imagesx(imagecreatefrompng($fx('tiny-16x16.png')));
            }
        },
        'imagick' => function () use ($fx) {
            for ($i = 0; $i < 50; $i++) {
                (new Imagick($fx('tiny-16x16.png')))->getImageWidth();
            }
        },
        'vips' => function () use ($fx) {
            for ($i = 0; $i < 50; $i++) {
                Jcupitt\Vips\Image::newFromFile($fx('tiny-16x16.png'))->width;
            }
        },
    ],
];

function measure(callable $fn, float $minTime, int $maxIter): array
{
    $fn(); // warmup (autoload, opcache, caches)
    $fn();
    gc_collect_cycles();

    $times = [];
    $start = hrtime(true);
    do {
        $t = hrtime(true);
        $fn();
        $times[] = (hrtime(true) - $t) / 1e6;
    } while (count($times) < $maxIter && (hrtime(true) - $start) / 1e9 < $minTime);

    sort($times);
    $n = count($times);

    return [
        'iterations' => $n,
        'median_ms' => $times[intdiv($n, 2)],
        'min_ms' => $times[0],
        'p90_ms' => $times[(int) floor($n * 0.9)],
    ];
}

$env = [
    'os' => trim((string) shell_exec('. /etc/os-release && echo "$PRETTY_NAME"')),
    'php' => PHP_VERSION,
    'gd' => gd_info()['GD Version'] ?? null,
    'imagemagick' => Imagick::getVersion()['versionString'] ?? null,
    'vips' => Jcupitt\Vips\Config::version(),
    'cpus' => (int) shell_exec('nproc'),
];
fwrite(STDERR, json_encode($env) . PHP_EOL);

$results = [];
foreach ($drivers as $driver) {
    $manager = ImageManager::usingDriver($driverClasses[$driver]);

    $all = $scenarios;
    foreach ($native as $name => $impls) {
        $all[$name] = [$impls[$driver], str_contains($name, 'x50') ? 50 : 1];
    }

    foreach ($all as $name => [$fn, $ops]) {
        if ($filter !== null && !preg_match('~' . $filter . '~', $name)) {
            continue;
        }
        try {
            $r = measure(fn() => $fn($manager, $driver), $minTime, $maxIter);
        } catch (Throwable $e) {
            $r = ['error' => $e::class . ': ' . $e->getMessage()];
        }
        $r += ['driver' => $driver, 'scenario' => $name, 'ops' => $ops];
        $results[] = $r;
        fwrite(STDERR, sprintf(
            "%-8s %-58s %s\n",
            $driver,
            $name,
            isset($r['error']) ? 'ERROR ' . $r['error'] : sprintf('%9.3f ms  (n=%d)', $r['median_ms'], $r['iterations']),
        ));
    }
}

$json = json_encode(['env' => $env, 'results' => $results], JSON_PRETTY_PRINT);
if (isset($opts['out'])) {
    @mkdir(dirname($opts['out']), 0777, true);
    file_put_contents($opts['out'], $json);
} else {
    echo $json, PHP_EOL;
}
