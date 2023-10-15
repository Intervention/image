<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\ColorTransformer;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class FillModifier implements ModifierInterface
{
    protected $gd_color;

    public function __construct(protected ColorInterface $color, protected ?Point $position = null)
    {
        $this->gd_color = ColorTransformer::colorToInteger($color);
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame);
            } else {
                $this->fillAllWithColor($frame);
            }
        }

        return $image;
    }

    protected function floodFillWithColor(Frame $frame): void
    {
        imagefill(
            $frame->getCore(),
            $this->position->getX(),
            $this->position->getY(),
            $this->gd_color
        );
    }

    protected function fillAllWithColor(Frame $frame): void
    {
        imagealphablending($frame->getCore(), true);
        imagefilledrectangle(
            $frame->getCore(),
            0,
            0,
            $frame->getSize()->getWidth() - 1,
            $frame->getSize()->getHeight() - 1,
            $this->gd_color
        );
    }

    protected function hasPosition(): bool
    {
        return !empty($this->position);
    }
}
