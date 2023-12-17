<?php

namespace Intervention\Image\Encoders;

use Intervention\Gif\Exception\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AutoEncoder implements EncoderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode(
            $this->encoderByMediaType(
                $image->origin()->mediaType()
            )
        );
    }

    /**
     * Return encoder matching to encode given media (mime) type
     *
     * @param string $type
     * @return EncoderInterface
     * @throws EncoderException
     */
    protected function encoderByMediaType(string $type): EncoderInterface
    {
        return match ($type) {
            'image/webp' => new WebpEncoder(),
            'image/avif' => new AvifEncoder(),
            'image/jpeg' => new JpegEncoder(),
            'image/bmp' => new BmpEncoder(),
            'image/gif' => new GifEncoder(),
            'image/png' => new PngEncoder(),
            'image/tiff' => new TiffEncoder(),
            default => throw new EncoderException('No encoder found for media type (' . $type . ').'),
        };
    }
}
