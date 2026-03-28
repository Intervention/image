<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResolutionModifier as GenericResolutionModifier;

class ResolutionModifier extends GenericResolutionModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $x = intval(round($this->x));
        $y = intval(round($this->y));

        foreach ($image as $frame) {
            imageresolution($frame->native(), $x, $y);
        }

        // GD returns 96x96 as resolution by default even if the image has no resolution.
        // This is problematic because it is impossible to tell whether the image
        // really has this resolution or whether it just corresponds to the default value.
        //
        // If the resolution was change to 96x96 (default resolution of GD) we mark
        // the resolution as changed to be able to distinguish it
        if ($x === 96 && $y === 96) {
            $image->core()->meta()->set('resolutionChanged', true);
        }

        return $image;
    }
}
