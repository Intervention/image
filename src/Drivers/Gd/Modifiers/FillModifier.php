<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @method bool hasPosition()
 * @property mixed $color
 * @property null|Point $position
 */
class FillModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->color($image);

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $color);
            } else {
                $this->fillAllWithColor($frame, $color);
            }
        }

        return $image;
    }

    private function color(ImageInterface $image): int
    {
        return $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->color)
        );
    }

    private function floodFillWithColor(Frame $frame, int $color): void
    {
        imagefill(
            $frame->native(),
            $this->position->x(),
            $this->position->y(),
            $color
        );
    }

    private function fillAllWithColor(Frame $frame, int $color): void
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
