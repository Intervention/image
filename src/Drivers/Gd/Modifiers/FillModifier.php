<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class FillModifier implements ModifierInterface
{
    use CanHandleColors;

    public function __construct(protected ColorInterface $color, protected ?Point $position = null)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->colorToInteger($this->color);

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $color);
            } else {
                $this->fillAllWithColor($frame, $color);
            }
        }

        return $image;
    }

    protected function floodFillWithColor(Frame $frame, int $color): void
    {
        imagefill(
            $frame->core(),
            $this->position->getX(),
            $this->position->getY(),
            $color
        );
    }

    protected function fillAllWithColor(Frame $frame, int $color): void
    {
        imagealphablending($frame->core(), true);
        imagefilledrectangle(
            $frame->core(),
            0,
            0,
            $frame->size()->width() - 1,
            $frame->size()->height() - 1,
            $color
        );
    }

    protected function hasPosition(): bool
    {
        return !empty($this->position);
    }
}
