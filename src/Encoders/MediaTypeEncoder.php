<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\AbstractEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class MediaTypeEncoder extends AbstractEncoder
{
    /**
     * Create new encoder instance
     *
     * @param null|string $mediaType Target media type for example "image/jpeg"
     * @param int $quality
     * @return void
     */
    public function __construct(
        public ?string $mediaType = null,
        public int $quality = self::DEFAULT_QUALITY
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $mediaType = is_null($this->mediaType) ? $image->origin()->mediaType() : $this->mediaType;

        return $image->encode(
            $this->encoderByMediaType($mediaType)
        );
    }

    /**
     * Return new encoder by given media (MIME) type
     *
     * @param string $mediaType
     * @throws EncoderException
     * @return EncoderInterface
     */
    protected function encoderByMediaType(string $mediaType): EncoderInterface
    {
        return match (strtolower($mediaType)) {
            'image/webp',
            'image/x-webp' => new WebpEncoder(quality: $this->quality),
            'image/avif',
            'image/x-avif' => new AvifEncoder(quality: $this->quality),
            'image/jpeg',
            'image/jpg',
            'image/pjpeg' => new JpegEncoder(quality: $this->quality),
            'image/bmp',
            'image/ms-bmp',
            'image/x-bitmap',
            'image/x-bmp',
            'image/x-ms-bmp',
            'image/x-win-bitmap',
            'image/x-windows-bmp',
            'image/x-xbitmap' => new BmpEncoder(),
            'image/gif' => new GifEncoder(),
            'image/png',
            'image/x-png' => new PngEncoder(),
            'image/tiff' => new TiffEncoder(quality: $this->quality),
            'image/jp2',
            'image/jpx',
            'image/jpm' => new Jpeg2000Encoder(quality: $this->quality),
            'image/heic',
            'image/heif', => new HeicEncoder(quality: $this->quality),
            default => throw new EncoderException('No encoder found for media type (' . $mediaType . ').'),
        };
    }
}
