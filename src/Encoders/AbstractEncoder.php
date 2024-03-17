<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class AbstractEncoder implements EncoderInterface
{
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
}
