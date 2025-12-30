<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Error;
use Intervention\Image\Drivers\AbstractEncoder;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\MediaType;

class MediaTypeEncoder extends AbstractEncoder
{
    /**
     * Encoder options
     *
     * @var array<int|string, mixed>
     */
    protected array $options = [];

    /**
     * Create new encoder instance
     *
     * @param null|string|MediaType $mediaType Target media type for example "image/jpeg"
     * @return void
     */
    public function __construct(public null|string|MediaType $mediaType = null, mixed ...$options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws NotSupportedException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $mediaType = is_null($this->mediaType) ? $image->origin()->mediaType() : $this->mediaType;

        return $image->encode(
            $this->encoderByMediaType($mediaType)
        );
    }

    /**
     * Return new encoder by given media (MIME) type
     *
     * @throws NotSupportedException
     */
    protected function encoderByMediaType(string|MediaType $mediaType): EncoderInterface
    {
        try {
            $mediaType = is_string($mediaType) ? MediaType::from($mediaType) : $mediaType;
        } catch (Error) {
            throw new NotSupportedException(
                'Unable to find encoder by unknown image media type "' . $mediaType . '"',
            );
        }

        return $mediaType->format()->encoder(...$this->options);
    }
}
