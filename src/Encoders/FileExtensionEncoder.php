<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Error;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\FileExtension;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FileExtensionEncoder extends AutoEncoder
{
    /**
     * Encoder options
     *
     * @var array<int|string, mixed>
     */
    protected array $options = [];

    /**
     * Create new encoder instance to encode to format of given file extension
     *
     * @param null|string|FileExtension $extension Target file extension for example "png"
     * @return void
     */
    public function __construct(public null|string|FileExtension $extension = null, mixed ...$options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws NotSupportedException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $extension = is_null($this->extension) ? $image->origin()->fileExtension() : $this->extension;

        if ($extension === null) {
            throw new NotSupportedException('Unable to find encoder by unknown origin file extension');
        }

        return $image->encode(
            $this->encoderByFileExtension(
                $extension
            )
        );
    }

    /**
     * Create matching encoder for given file extension
     *
     * @throws NotSupportedException
     */
    protected function encoderByFileExtension(string|FileExtension $extension): EncoderInterface
    {
        if ($extension === '') {
            throw new NotSupportedException('Unable to find encoder for empty file extension');
        }

        try {
            $extension = is_string($extension) ? FileExtension::from(strtolower($extension)) : $extension;
        } catch (Error) {
            throw new NotSupportedException(
                'Unable to find encoder for unknown image file extension "' . $extension . '"',
            );
        }

        return $extension->format()->encoder(...$this->options);
    }
}
