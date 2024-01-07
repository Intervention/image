<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class MediaTypeEncoder implements EncoderInterface
{
    /**
     * Create new encoder instance to encode given media (mime) type
     *
     * @param null|string $type
     * @param int $quality
     * @return void
     */
    public function __construct(protected ?string $type = null, protected int $quality = 75)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $type = is_null($this->type) ? $image->origin()->mediaType() : $this->type;

        return $image->encode(
            $this->encoderByMediaType($type)
        );
    }

    /**
     * Return new encoder by given media (MIME) type
     *
     * @param string $type
     * @return EncoderInterface
     * @throws EncoderException
     */
    protected function encoderByMediaType(string $type): EncoderInterface
    {
        return match (strtolower($type)) {
            'image/webp' => new WebpEncoder($this->quality),
            'image/avif' => new AvifEncoder($this->quality),
            'image/jpeg' => new JpegEncoder($this->quality),
            'image/bmp' => new BmpEncoder(),
            'image/gif' => new GifEncoder(),
            'image/png' => new PngEncoder(),
            'image/tiff' => new TiffEncoder($this->quality),
            'image/jp2', 'image/jpx', 'image/jpm' => new Jpeg2000Encoder($this->quality),
            default => throw new EncoderException('No encoder found for media type (' . $type . ').'),
        };
    }
}
