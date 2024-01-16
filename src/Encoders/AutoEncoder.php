<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AutoEncoder extends MediaTypeEncoder
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        return $image->encode(
            $this->encoderByMediaType(
                $image->origin()->mediaType()
            )
        );
    }
}
