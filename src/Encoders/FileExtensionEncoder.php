<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FileExtensionEncoder extends AutoEncoder
{
    /**
     * Create new encoder instance to encode to format of given file extension
     *
     * @param null|string $extension
     * @param mixed $options
     * @return void
     */
    public function __construct(protected ?string $extension = null, mixed ...$options)
    {
        parent::__construct(null, ...$options);
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

        return match (strtolower($extension)) {
            'webp' => new WebpEncoder(quality: $this->quality),
            'avif' => new AvifEncoder(quality: $this->quality),
            'jpeg', 'jpg' => new JpegEncoder(quality: $this->quality),
            'bmp' => new BmpEncoder(),
            'gif' => new GifEncoder(),
            'png' => new PngEncoder(),
            'tiff', 'tif' => new TiffEncoder(quality: $this->quality),
            'jp2', 'j2k', 'jpf', 'jpm', 'jpg2', 'j2c', 'jpc', 'jpx' => new Jpeg2000Encoder(quality: $this->quality),
            'heic', 'heif' => new HeicEncoder(quality: $this->quality),
            default => throw new EncoderException('No encoder found for file extension (' . $extension . ').'),
        };
    }
}
