<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\LogicException;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Traits\CanBuildStream;

abstract class AbstractEncoder implements EncoderInterface
{
    use CanBuildStream;

    /**
     * Default encoding quality.
     */
    public const int DEFAULT_QUALITY = 75;

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws LogicException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        if ($this instanceof SpecializedInterface) {
            throw new LogicException(
                "Specialized class '" . static::class . "' must override encode()"
            );
        }

        return $image->encode($this);
    }

    /**
     * Build new stream, run callback with it and return result as encoded image.
     *
     * @throws InvalidArgumentException
     * @throws StreamException
     */
    protected function createEncodedImage(callable $callback, ?string $mediaType = null): EncodedImage
    {
        $stream = self::buildStreamOrFail();
        $callback($stream);

        return is_string($mediaType) ? new EncodedImage($stream, $mediaType) : new EncodedImage($stream);
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::setOptions()
     *
     * @throws InvalidArgumentException
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
