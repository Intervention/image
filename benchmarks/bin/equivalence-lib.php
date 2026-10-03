<?php

declare(strict_types=1);

function frameState(Imagick $native): array
{
    $frames = [];
    foreach ($native as $frame) {
        $bg = $frame->getImageBackgroundColor();
        $properties = array_filter(
            $frame->getImageProperties(),
            fn(string $key): bool => !str_starts_with($key, 'date:'),
            ARRAY_FILTER_USE_KEY,
        );
        ksort($properties);
        $frames[] = [
            'signature' => $frame->getImageSignature(),
            'size' => $frame->getImageWidth() . 'x' . $frame->getImageHeight(),
            'page' => $frame->getImagePage(),
            'dispose' => $frame->getImageDispose(),
            'delay' => $frame->getImageDelay(),
            'background' => $bg->getColorAsString() . ' a=' . $bg->getColorValue(Imagick::COLOR_ALPHA),
            'colorspace' => $frame->getImageColorspace(),
            'type' => $frame->getImageType(),
            'alpha' => $frame->getImageAlphaChannel(),
            'depth' => $frame->getImageDepth(),
            'format' => $frame->getImageFormat(),
            'orientation' => $frame->getImageOrientation(),
            'profiles' => array_map('md5', $frame->getImageProfiles('*')),
            'properties' => md5(serialize($properties)),
        ];
    }

    return $frames;
}
