<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanBuildFilePointer;

abstract class AbstractEncoder implements EncoderInterface
{
    use CanBuildFilePointer;

    public const DEFAULT_QUALITY = 75;

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode($this);
    }

    /**
     * Build new file pointer, run callback with it and return result as encoded image
     *
     * @throws RuntimeException
     */
    protected function createEncodedImage(callable $callback, ?string $mediaType = null): EncodedImage
    {
        $pointer = $this->buildFilePointer();
        $callback($pointer);

        return is_string($mediaType) ? new EncodedImage($pointer, $mediaType) : new EncodedImage($pointer);
    }
}
