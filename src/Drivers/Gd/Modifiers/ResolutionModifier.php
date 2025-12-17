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
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $x = intval(round($this->x));
        $y = intval(round($this->y));

        foreach ($image as $frame) {
            $result = imageresolution($frame->native(), $x, $y);
            if ($result === false) {
                throw new ModifierException('Failed to set image resolution');
            }
        }

        if ($x === 96 && $y === 96) {
            // mark resolution as non default somewhere
            // TODO: refactor change mark
            $image->core()->resolutionChanged = true;
        }


        return $image;
    }
}
