<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract\AbstractInputHandler;

class InputHandler extends AbstractInputHandler
{
    protected $decoders = [
        Decoders\ImageObjectDecoder::class,
        Decoders\FilePointerImageDecoder::class,
        Decoders\ArrayColorDecoder::class,
        Decoders\HtmlColorNameDecoder::class,
        Decoders\RgbStringColorDecoder::class,
        Decoders\HexColorDecoder::class,
        Decoders\TransparentColorDecoder::class,
        Decoders\FilePathImageDecoder::class,
        Decoders\BinaryImageDecoder::class,
        Decoders\DataUriImageDecoder::class,
        Decoders\Base64ImageDecoder::class,
    ];
}
