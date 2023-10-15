<?php

namespace Intervention\Image\Drivers\Imagick;

use Intervention\Image\Drivers\Abstract\AbstractInputHandler;

class InputHandler extends AbstractInputHandler
{
    protected $decoders = [
        Decoders\ImageObjectDecoder::class,
        Decoders\FilePointerImageDecoder::class,
        Decoders\HtmlColorNameDecoder::class,
        Decoders\HexColorDecoder::class,
        Decoders\RgbStringColorDecoder::class,
        // Decoders\TransparentColorDecoder::class,
        Decoders\FilePathImageDecoder::class,
        Decoders\BinaryImageDecoder::class,
        Decoders\DataUriImageDecoder::class,
        Decoders\Base64ImageDecoder::class,
    ];
}
