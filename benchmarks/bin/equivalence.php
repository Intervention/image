<?php

declare(strict_types=1);

/**
 * Dump the observable state of images decoded by the Imagick driver, to diff
 * the output of two code versions:
 *
 *   php bin/equivalence.php > before.json   # old code
 *   php bin/equivalence.php > after.json    # new code
 *   diff before.json after.json
 */

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/equivalence-lib.php';

$edgeDir = __DIR__ . '/../fixtures/edge';
@mkdir($edgeDir, 0777, true);

// craft edge cases once (deterministic content)
$base = function (int $w = 64, int $h = 48): Imagick {
    $im = new Imagick();
    $im->newPseudoImage($w, $h, 'gradient:#ff0000-#0000ff');
    $im->setImageColorspace(Imagick::COLORSPACE_SRGB);
    $im->setImageDepth(8);

    return $im;
};
$edge = [
    'page-offset.png' => function () use ($base) {
        $im = $base();
        $im->setImagePage(100, 80, 10, 20);

        return $im;
    },
    'page-larger.png' => function () use ($base) {
        $im = $base();
        $im->setImagePage(100, 80, 0, 0);

        return $im;
    },
    'palette.png' => function () use ($base) {
        $im = $base();
        $im->quantizeImage(16, Imagick::COLORSPACE_SRGB, 0, false, false);
        $im->setImageType(Imagick::IMGTYPE_PALETTE);
        $im->setOption('png:format', 'png8');

        return $im;
    },
    'gray.png' => function () use ($base) {
        $im = $base();
        $im->transformImageColorspace(Imagick::COLORSPACE_GRAY);

        return $im;
    },
    'gray-red-bkgd.png' => function () use ($base) {
        $im = $base();
        $im->transformImageColorspace(Imagick::COLORSPACE_GRAY);
        $im->setImageBackgroundColor('#ff0000');

        return $im;
    },
    'red-bkgd.png' => function () use ($base) {
        $im = $base();
        $im->setImageBackgroundColor('#00ff00');

        return $im;
    },
    'alpha.png' => function () use ($base) {
        $im = $base();
        $im->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
        $im->evaluateImage(Imagick::EVALUATE_MULTIPLY, 0.5, Imagick::CHANNEL_ALPHA);

        return $im;
    },
    '16bit.png' => function () use ($base) {
        $im = $base();
        $im->setImageDepth(16);

        return $im;
    },
    'alpha.webp' => function () use ($base) {
        $im = $base();
        $im->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
        $im->evaluateImage(Imagick::EVALUATE_MULTIPLY, 0.5, Imagick::CHANNEL_ALPHA);

        return $im;
    },
    'single-offset.gif' => function () use ($base) {
        $im = $base();
        $im->setImagePage(100, 80, 5, 7);

        return $im;
    },
    'image.bmp' => fn() => $base(),
    'image.tif' => fn() => $base(),
    'cmyk.tif' => function () use ($base) {
        $im = $base();
        $im->transformImageColorspace(Imagick::COLORSPACE_CMYK);

        return $im;
    },
    'multipage.tif' => function () use ($base) {
        $im = new Imagick();
        $im->addImage($base());
        $im->addImage($base(32, 32));

        return $im;
    },
];
foreach ($edge as $name => $build) {
    $path = $edgeDir . '/' . $name;
    if (!is_file($path)) {
        $im = $build();
        $im->setImageFormat(pathinfo($name, PATHINFO_EXTENSION));
        $im->writeImages($path, true);
    }
}

$files = array_merge(
    glob(__DIR__ . '/../../tests/resources/*.*'),
    glob(__DIR__ . '/../../tests/resources/*/*.*'),
    glob(__DIR__ . '/../fixtures/*.*'),
    glob($edgeDir . '/*.*'),
);
sort($files);

// pixel signature of re-decoded output (encoders may embed timestamps)
function encodedState(ImageInterface $image, Format $format): string
{
    try {
        $blob = (string) (clone $image)->encodeUsingFormat($format);
        $im = new Imagick();
        $im->readImageBlob($blob);
        $sigs = [];
        foreach ($im as $frame) {
            $sigs[] = $frame->getImageSignature() . '/' . $frame->getImageType() . '/' . json_encode($frame->getImagePage());
        }

        return implode(',', $sigs);
    } catch (Throwable $e) {
        return 'error: ' . $e::class;
    }
}

$manager = ImageManager::usingDriver(Driver::class);
$result = [];
foreach ($files as $file) {
    $key = basename(dirname($file)) . '/' . basename($file);
    try {
        $state = [];
        foreach (['decodePath' => $file, 'decodeBinary' => file_get_contents($file)] as $method => $input) {
            $image = $manager->{$method}($input);
            $state[$method] = [
                'mediaType' => $image->origin()->mediaType(),
                'frames' => frameState($image->core()->native()),
                'colorAt' => (string) $image->colorAt(0, 0),
                'backgroundColor' => (string) $image->backgroundColor(),
            ];
        }
        $image = $manager->decodePath($file);
        $state['encoded'] = [];
        foreach ([Format::PNG, Format::JPEG, Format::GIF, Format::WEBP, Format::TIFF] as $format) {
            $state['encoded'][$format->name] = encodedState($image, $format);
        }
        $state['modified'] = [
            'rotate' => frameState((clone $image)->rotate(33)->core()->native()),
            'resizeCanvas' => frameState((clone $image)->resizeCanvas(300, 300)->core()->native()),
            'resize' => frameState((clone $image)->resize(40, 30)->core()->native()),
            'crop' => frameState((clone $image)->crop(10, 10, 2, 2)->core()->native()),
            'insert' => frameState($manager->createImage(200, 200)->insert($file, 5, 5)->core()->native()),
            'removeAnimation' => frameState((clone $image)->removeAnimation()->core()->native()),
            'fillTransparent' => frameState((clone $image)->fillTransparentAreas('#00ff00')->core()->native()),
        ];
        $result[$key] = $state;
    } catch (Throwable $e) {
        $result[$key] = 'error: ' . $e::class . ': ' . $e->getMessage();
    }
}

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
fwrite(STDERR, count($result) . " files checked\n");
