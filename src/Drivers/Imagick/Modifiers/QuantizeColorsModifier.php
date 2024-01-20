<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $limit
 * @property mixed $background
 */
class QuantizeColorsModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->limit <= 0) {
            throw new InputException('Quantization limit must be greater than 0.');
        }

        // no color reduction if the limit is higher than the colors in the img
        if ($this->limit > $image->core()->native()->getImageColors()) {
            return $image;
        }

        foreach ($image as $frame) {
            $frame->native()->quantizeImage(
                $this->limit,
                $frame->native()->getImageColorspace(),
                0,
                false,
                false
            );
        }

        return $image;
    }
}
