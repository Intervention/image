<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder;
use Intervention\Image\Colors\Rgb\Decoders\HtmlColornameDecoder;
use Intervention\Image\Colors\Rgb\Decoders\TransparentColorDecoder;
use Intervention\Image\Drivers\Abstract\AbstractInputHandler;
use Intervention\Image\Drivers\Gd\Decoders\ImageObjectDecoder;
use Intervention\Image\Drivers\Gd\Decoders\ColorObjectDecoder;
use Intervention\Image\Drivers\Gd\Decoders\FilePointerImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\BinaryImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\DataUriImageDecoder;
use Intervention\Image\Drivers\Gd\Decoders\Base64ImageDecoder;

class InputHandler extends AbstractInputHandler
{
    protected array $decoders = [
        ImageObjectDecoder::class,
        ColorObjectDecoder::class,
        HexColorDecoder::class,
        StringColorDecoder::class,
        TransparentColorDecoder::class,
        HtmlColornameDecoder::class,
        FilePointerImageDecoder::class,
        FilePathImageDecoder::class,
        BinaryImageDecoder::class,
        DataUriImageDecoder::class,
        Base64ImageDecoder::class,
    ];
}
