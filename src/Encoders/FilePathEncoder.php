<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;

class FilePathEncoder extends FileExtensionEncoder
{
    /**
     * Create new encoder instance to encode to format of file extension in given path
     *
     * @param null|string $path
     * @param int $quality
     * @return void
     */
    public function __construct(protected ?string $path = null, protected int $quality = 75)
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
                is_null($this->path) ? $image->origin()->fileExtension() : pathinfo($this->path, PATHINFO_EXTENSION)
            )
        );
    }
}
