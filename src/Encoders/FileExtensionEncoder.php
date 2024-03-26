<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FileExtensionEncoder extends AutoEncoder
{
    protected array $options = [];

    /**
     * Create new encoder instance to encode to format of given file extension
     *
     * @param null|string $extension Target file extension for example "png"
     * @return void
     */
    public function __construct(public ?string $extension = null, mixed ...$options)
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
     * @throws EncoderException
     * @return EncoderInterface
     */
    protected function encoderByFileExtension(?string $extension): EncoderInterface
    {
        if (empty($extension)) {
            throw new EncoderException('No encoder found for empty file extension.');
        }

        return match (strtolower($extension)) {
            'webp' => new WebpEncoder(...$this->options),
            'avif' => new AvifEncoder(...$this->options),
            'jpeg', 'jpg' => new JpegEncoder(...$this->options),
            'bmp' => new BmpEncoder(...$this->options),
            'gif' => new GifEncoder(...$this->options),
            'png' => new PngEncoder(...$this->options),
            'tiff', 'tif' => new TiffEncoder(...$this->options),
            'jp2', 'j2k', 'jpf', 'jpm', 'jpg2', 'j2c', 'jpc', 'jpx' => new Jpeg2000Encoder(...$this->options),
            'heic', 'heif' => new HeicEncoder(...$this->options),
            default => throw new EncoderException('No encoder found for file extension (' . $extension . ').'),
        };
    }
}
