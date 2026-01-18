<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FillModifier as GenericFillModifier;

class FillModifier extends GenericFillModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorProcessor($image)->colorToNative(
            $this->color()
        );

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $color);
            } else {
                $this->fillAllWithColor($frame, $color);
            }
        }

        return $image;
    }

    /**
     * @throws ModifierException
     */
    private function floodFillWithColor(FrameInterface $frame, int $color): void
    {
        imagefill(
            $frame->native(),
            $this->position->x(),
            $this->position->y(),
            $color
        );
    }

    /**
     * @throws ModifierException
     */
    private function fillAllWithColor(FrameInterface $frame, int $color): void
    {
        imagealphablending($frame->native(), true);
        imagefilledrectangle(
            $frame->native(),
            0,
            0,
            $frame->size()->width() - 1,
            $frame->size()->height() - 1,
            $color
        );
    }
}
