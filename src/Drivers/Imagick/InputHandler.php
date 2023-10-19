<?php

namespace Intervention\Image\Drivers\Imagick;

use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Intervention\Image\Drivers\Abstract\AbstractInputHandler;
use Intervention\Image\Drivers\Imagick\Decoders\ImageObjectDecoder;
use Intervention\Image\Drivers\Imagick\Decoders\ColorObjectDecoder;
use Intervention\Image\Drivers\Imagick\Decoders\FilePointerImageDecoder;
use Intervention\Image\Drivers\Imagick\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Imagick\Decoders\BinaryImageDecoder;
use Intervention\Image\Drivers\Imagick\Decoders\DataUriImageDecoder;
use Intervention\Image\Drivers\Imagick\Decoders\Base64ImageDecoder;

class InputHandler extends AbstractInputHandler
{
    protected $decoders = [
        ImageObjectDecoder::class,
        ColorObjectDecoder::class,
        HexColorDecoder::class,
        StringColorDecoder::class,
        // Decoders\TransparentColorDecoder::class,
        HtmlColornameDecoder::class,
        FilePointerImageDecoder::class,
        FilePathImageDecoder::class,
        BinaryImageDecoder::class,
        DataUriImageDecoder::class,
        Base64ImageDecoder::class,
    ];
}
