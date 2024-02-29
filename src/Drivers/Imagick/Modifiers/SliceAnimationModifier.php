<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\AnimationException;
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
        if ($this->offset >= $image->count()) {
            throw new AnimationException('Offset is not in the range of frames.');
        }

        $image->core()->slice($this->offset, $this->length);

        return $image;
    }
}
