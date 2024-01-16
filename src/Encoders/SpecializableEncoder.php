<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class SpecializableEncoder implements EncoderInterface
{
    public const DEFAULT_QUALITY = 75;

    /**
     * Target quality of encoder
     *
     * @param int $quality
     */
    public int $quality = self::DEFAULT_QUALITY;

    /**
     * Create new encoder instance
     *
     * @param mixed $options
     * @return void
     */
    public function __construct(mixed ...$options)
    {
        if (is_array($options) && array_is_list($options)) {
            $this->quality = $options[0] ?? self::DEFAULT_QUALITY;
        } else {
            $this->quality = $options['quality'] ?? self::DEFAULT_QUALITY;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode($this);
    }
}
