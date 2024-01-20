<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder as RgbHexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder as RgbStringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Intervention\Image\Colors\Rgb\Decoders\TransparentColorDecoder;
use Intervention\Image\Colors\Cmyk\Decoders\StringColorDecoder as CmykStringColorDecoder;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder as HsvStringColorDecoder;
use Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder as HslStringColorDecoder;
use Intervention\Image\Drivers\AbstractInputHandler;
use Intervention\Image\Drivers\Gd\Decoders\ImageObjectDecoder;
use Intervention\Image\Drivers\Gd\Decoders\ColorObjectDecoder;
use Intervention\Image\Drivers\Gd\Decoders\FilePointerImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\BinaryImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\DataUriImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\Base64ImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\SplFileInfoImageDecoder;

class InputHandler extends AbstractInputHandler
{
    protected array $decoders = [
        ImageObjectDecoder::class,
        ColorObjectDecoder::class,
        RgbHexColorDecoder::class,
        RgbStringColorDecoder::class,
        CmykStringColorDecoder::class,
        HsvStringColorDecoder::class,
        HslStringColorDecoder::class,
        TransparentColorDecoder::class,
        HtmlColornameDecoder::class,
        FilePointerImageDecoder::class,
        FilePathImageDecoder::class,
        SplFileInfoImageDecoder::class,
        BinaryImageDecoder::class,
        DataUriImageDecoder::class,
        Base64ImageDecoder::class,
    ];
}
