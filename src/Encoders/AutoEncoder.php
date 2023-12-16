<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AutoEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode(
            match ($image->origin()->mimetype()) {
                'image/webp' => new WebpEncoder(),
                'image/avif' => new AvifEncoder(),
                'image/jpeg' => new JpegEncoder(),
                'image/bmp' => new BmpEncoder(),
                'image/gif' => new GifEncoder(),
                default => new PngEncoder(),
            }
        );
    }
}
