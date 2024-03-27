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
    protected array $options = [];

    /**
     * Create new encoder instance
     *
     * @param null|string $mediaType Target media type for example "image/jpeg"
     * @return void
     */
    public function __construct(public ?string $mediaType = null, mixed ...$options)
    {
        $this->options = $options;
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
            'image/x-webp' => new WebpEncoder(...$this->options),
            'image/avif',
            'image/x-avif' => new AvifEncoder(...$this->options),
            'image/jpeg',
            'image/jpg',
            'image/pjpeg' => new JpegEncoder(...$this->options),
            'image/bmp',
            'image/ms-bmp',
            'image/x-bitmap',
            'image/x-bmp',
            'image/x-ms-bmp',
            'image/x-win-bitmap',
            'image/x-windows-bmp',
            'image/x-xbitmap' => new BmpEncoder(...$this->options),
            'image/gif' => new GifEncoder(...$this->options),
            'image/png',
            'image/x-png' => new PngEncoder(...$this->options),
            'image/tiff' => new TiffEncoder(...$this->options),
            'image/jp2',
            'image/jpx',
            'image/jpm' => new Jpeg2000Encoder(...$this->options),
            'image/heic',
            'image/heif', => new HeicEncoder(...$this->options),
            default => throw new EncoderException('No encoder found for media type (' . $mediaType . ').'),
        };
    }
}
