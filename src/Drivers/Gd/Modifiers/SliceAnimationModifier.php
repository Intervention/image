<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\SliceAnimationModifier as GenericSliceAnimationModifier;
use Intervention\Image\Traits\IsDriverSpecialized;

/**
 * @property int $offset
 * @property null|int $length
 */
class SliceAnimationModifier extends GenericSliceAnimationModifier implements SpecializedInterface
{
    use IsDriverSpecialized;

    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->offset >= $image->count()) {
            throw new AnimationException('Offset is not in the range of frames.');
        }

        $image->core()->slice($this->offset, $this->length);

        return $image;
    }
}
