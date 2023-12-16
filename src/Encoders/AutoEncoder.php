<?php

namespace Intervention\Image\Encoders;

use Intervention\Gif\Exception\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AutoEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $type = $image->origin()->mimetype();
        return $image->encode(
            match ($type) {
                'image/webp' => new WebpEncoder(),
                'image/avif' => new AvifEncoder(),
                'image/jpeg' => new JpegEncoder(),
                'image/bmp' => new BmpEncoder(),
                'image/gif' => new GifEncoder(),
                'image/png' => new PngEncoder(),
                default => throw new EncoderException('No encoder found for media type (' . $type . ').'),
            }
        );
    }
}
