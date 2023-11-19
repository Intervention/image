<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Interfaces\ImageInterface;

class FillModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->color();

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $color);
            } else {
                $this->fillAllWithColor($frame, $color);
            }
        }

        return $image;
    }

    private function color(): int
    {
        return $this->driver()->colorToNative(
            $this->driver()->handleInput($this->color),
            new Colorspace()
        );
    }

    private function floodFillWithColor(Frame $frame, int $color): void
    {
        imagefill(
            $frame->data(),
            $this->position->x(),
            $this->position->y(),
            $color
        );
    }

    private function fillAllWithColor(Frame $frame, int $color): void
    {
        imagealphablending($frame->data(), true);
        imagefilledrectangle(
            $frame->data(),
            0,
            0,
            $frame->size()->width() - 1,
            $frame->size()->height() - 1,
            $color
        );
    }
}
