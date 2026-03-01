<?php

declare(strict_types=1);

namespace Intervention\Image\Encoders;

use Intervention\Image\Drivers\AbstractEncoder;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FormatEncoder extends AbstractEncoder
{
    /**
     * Encoder options.
     *
     * @var array<int|string, mixed>
     */
    protected array $options = [];

    /**
     * Create new encoder instance to encode to given format.
     *
     * @return void
     */
    public function __construct(protected ?Format $format = null, mixed ...$options)
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
        try {
            $format = is_null($this->format) ? $image->origin()->format() : $this->format;
        } catch (NotSupportedException $e) {
            throw new NotSupportedException('Unable to find encoder by unknown origin image format', previous: $e);
        }

        return $format->encoder(...$this->options)->encode($image);
    }
}
