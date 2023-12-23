<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AutoEncoder extends MediaTypeEncoder
{
    /**
     * Create new encoder instance
     *
     * @param int $quality
     * @return void
     */
    public function __construct(protected int $quality = 75)
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
            $this->encoderByMediaType(
                $image->origin()->mediaType()
            )
        );
    }
}
