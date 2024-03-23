<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\SliceAnimationModifier as GenericSliceAnimationModifier;

class SliceAnimationModifier extends GenericSliceAnimationModifier implements SpecializedInterface
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
