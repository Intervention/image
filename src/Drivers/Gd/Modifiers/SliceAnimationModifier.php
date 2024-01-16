<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $offset
 * @property null|int $length
 */
class SliceAnimationModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $image->core()->slice($this->offset, $this->length);

        return $image;
    }
}
