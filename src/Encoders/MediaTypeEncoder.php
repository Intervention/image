<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class MediaTypeEncoder extends SpecializableEncoder implements EncoderInterface
{
    public function __construct(protected ?string $type = null, ...$options)
    {
        parent::__construct(...$options);
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
            'image/webp' => new WebpEncoder(quality: $this->quality),
            'image/avif' => new AvifEncoder(quality: $this->quality),
            'image/jpeg' => new JpegEncoder(quality: $this->quality),
            'image/bmp' => new BmpEncoder(),
            'image/gif' => new GifEncoder(),
            'image/png' => new PngEncoder(),
            'image/tiff' => new TiffEncoder(quality: $this->quality),
            'image/jp2', 'image/jpx', 'image/jpm' => new Jpeg2000Encoder(quality: $this->quality),
            'image/heic', 'image/heif', => new HeicEncoder(quality: $this->quality),
            default => throw new EncoderException('No encoder found for media type (' . $type . ').'),
        };
    }
}
