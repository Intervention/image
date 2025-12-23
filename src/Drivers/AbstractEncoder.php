<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanBuildFilePointer;

abstract class AbstractEncoder implements EncoderInterface
{
    use CanBuildFilePointer;

    /**
     * Default encoding quality
     */
    public const int DEFAULT_QUALITY = 75;

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
     */
    protected function createEncodedImage(callable $callback, ?string $mediaType = null): EncodedImage
    {
        $pointer = $this->buildFilePointerOrFail();
        $callback($pointer);

        return is_string($mediaType) ? new EncodedImage($pointer, $mediaType) : new EncodedImage($pointer);
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::setOptions()
     */
    public function setOptions(mixed ...$options): self
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, (string) $key)) {
                throw new InvalidArgumentException(
                    'Option $' . $key . ' does not exist on ' . $this::class,
                );
            }
            $this->{$key} = $value;
        }

        return $this;
    }
}
