<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;

class FilePathEncoder extends FileExtensionEncoder
{
    /**
     * Create new encoder instance to encode to format of file extension in given path
     *
     * @param null|string $path
     * @param mixed $options
     * @return void
     */
    public function __construct(protected ?string $path = null, mixed ...$options)
    {
        parent::__construct(
            is_null($path) ? $path : pathinfo($path, PATHINFO_EXTENSION),
            ...$options
        );
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
                is_null($this->path) ? $image->origin()->fileExtension() : pathinfo($this->path, PATHINFO_EXTENSION)
            )
        );
    }
}
