<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class AbstractEncoder implements EncoderInterface
{
    public const DEFAULT_QUALITY = 75;
    public const DEFAULT_COMPRESSION = 0;
    public const DEFAULT_BIT_DEPTH = 8;

    public int $quality = self::DEFAULT_QUALITY;
    public int $compression = self::DEFAULT_COMPRESSION;
    public int $bitDepth = self::DEFAULT_BIT_DEPTH;

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
            $this->compression = $options[1] ?? self::DEFAULT_COMPRESSION;
            $this->bitDepth = $options[2] ?? self::DEFAULT_BIT_DEPTH;
        } else {
            $this->quality = $options['quality'] ?? self::DEFAULT_QUALITY;
            $this->compression = $options['compression'] ?? self::DEFAULT_COMPRESSION;
            $this->bitDepth = $options['bitDepth'] ?? self::DEFAULT_BIT_DEPTH;
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
