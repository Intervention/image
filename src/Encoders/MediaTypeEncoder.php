<?php

namespace Intervention\Image\Encoders;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;

class MediaTypeEncoder extends AutoEncoder
{
    /**
     * Create new encoder instance to encode given media (mime) type
     *
     * @param null|string $type
     * @return void
     */
    public function __construct(protected ?string $type = null)
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
                is_null($this->type) ? $image->origin()->mediaType() : $this->type
            )
        );
    }
}
