<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;

class RemoveAnimationModifier extends SpecializableModifier
{
    public function __construct(public int|string $position = 0)
    {
    }

    /**
     * @throws RuntimeException
     */
    protected function selectedFrame(ImageInterface $image): FrameInterface
    {
        return $image->core()->frame($this->normalizePosition($image));
    }

    /**
     * Return the position of the selected frame as integer
     *
     * @param ImageInterface $image
     * @throws InputException
     * @return int
     */
    protected function normalizePosition(ImageInterface $image): int
    {
        if (is_int($this->position)) {
            return $this->position;
        }

        if (is_numeric($this->position)) {
            return (int) $this->position;
        }

        // calculate position from percentage value
        if (preg_match("/^(?P<percent>[0-9]{1,3})%$/", $this->position, $matches) != 1) {
            throw new InputException(
                'Position must be either integer or a percent value as string.'
            );
        }

        $total = count($image);
        $position = intval(round($total / 100 * intval($matches['percent'])));

        return $position == $total ? $position - 1 : $position;
    }
}
