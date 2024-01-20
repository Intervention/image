<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;

class RemoveAnimationModifier extends SpecializableModifier
{
    public function __construct(public int|string $position = 0)
    {
        //
    }

    public function chosenFrame(ImageInterface $image, int|string $position): FrameInterface
    {
        if (is_int($position)) {
            return $image->core()->frame($position);
        }

        if (preg_match("/^(?P<percent>[0-9]{1,3})%$/", $position, $matches) != 1) {
            throw new InputException(
                'Position must be either integer or a percent value as string.'
            );
        }

        $total = count($image);
        $position = intval(round($total / 100 * intval($matches['percent'])));
        $position = $position == $total ? $position - 1 : $position;

        return $image->core()->frame($position);
    }
}
