<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ModifierInterface;

abstract class DELETE___AbstractRemoveAnimationModifier implements ModifierInterface
{
    protected function chosenFrame($image, int|string $position): FrameInterface
    {
        if (is_int($position)) {
            return $image->frame($position);
        }

        if (preg_match("/^(?P<percent>[0-9]{1,3})%$/", $position, $matches) != 1) {
            throw new InputException(
                'Input value of Image::removeAnimation() must be either integer or a percent value as string.'
            );
        }

        $total = count($image);
        $position = intval(round($total / 100 * intval($matches['percent'])));
        $position = $position == $total ? $position - 1 : $position;

        return $image->frame($position);
    }
}
