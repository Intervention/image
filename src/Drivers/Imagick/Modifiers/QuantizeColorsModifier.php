<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\QuantizeColorsModifier as GenericQuantizeColorsModifier;

class QuantizeColorsModifier extends GenericQuantizeColorsModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->limit <= 0) {
            throw new InvalidArgumentException('Quantization limit must be greater than 0');
        }

        // no color reduction if the limit is higher than the colors in the img
        if ($this->limit > $image->core()->native()->getImageColors()) {
            return $image;
        }

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->quantizeImage(
                    $this->limit,
                    $frame->native()->getImageColorspace(),
                    0,
                    false,
                    false
                );
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to process quantization',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to process quantization',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
