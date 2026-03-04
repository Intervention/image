<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Encoders\BmpEncoder as GenericBmpEncoder;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class BmpEncoder extends GenericBmpEncoder implements SpecializedInterface
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
            imagebmp($image->core()->native(), $stream, false);
        }, 'image/bmp');
    }
}
