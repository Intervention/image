<?php

namespace Intervention\Image\Encoders;

use Intervention\Gif\Exception\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FileExtensionEncoder extends AutoEncoder
{
    /**
     * Create new encoder instance to encode to format of given file extension
     *
     * @param null|string $extension
     * @return void
     */
    public function __construct(protected ?string $extension = null)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode(
            $this->encoderByFileExtension(
                is_null($this->extension) ? $image->origin()->fileExtension() : $this->extension
            )
        );
    }

    /**
     * Create matching encoder for given file extension
     *
     * @param string $extension
     * @return EncoderInterface
     * @throws EncoderException
     */
    protected function encoderByFileExtension(?string $extension): EncoderInterface
    {
        if (empty($extension)) {
            throw new EncoderException('No encoder found for empty file extension.');
        }

        return match ($extension) {
            'webp' => new WebpEncoder(),
            'avif' => new AvifEncoder(),
            'jpeg', 'jpg' => new JpegEncoder(),
            'bmp' => new BmpEncoder(),
            'gif' => new GifEncoder(),
            'png' => new PngEncoder(),
            'tiff', 'tif' => new TiffEncoder(),
            default => throw new EncoderException('No encoder found for file extension (' . $extension . ').'),
        };
    }
}
