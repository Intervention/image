<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Encoders\AvifEncoder as GenericAvifEncoder;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class AvifEncoder extends GenericAvifEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws InvalidArgumentException
     * @throws StreamException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $this->createEncodedImage(function ($stream) use ($image): void {
            imageavif($image->core()->native(), $stream, $this->quality);
        }, 'image/avif');
    }
}
