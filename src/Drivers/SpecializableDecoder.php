<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use Intervention\Image\Traits\CanBeDriverSpecialized;

abstract class SpecializableDecoder extends AbstractDecoder implements SpecializableInterface
{
    use CanBeDriverSpecialized;

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        throw new DecoderException('Decoder must be specialized by the driver first.');
    }
}
