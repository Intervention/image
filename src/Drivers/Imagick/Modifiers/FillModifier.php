<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class FillModifier implements ModifierInterface
{
    public function __construct(
        protected Color $color,
        protected ?Point $position = null
    ) {
        //
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
        $target = $frame->getCore()->getImagePixelColor(
            $this->position->getX(),
            $this->position->getY()
        );

        $frame->getCore()->floodfillPaintImage(
            $this->color->getPixel(),
            100,
            $target,
            $this->position->getX(),
            $this->position->getY(),
            false,
            Imagick::CHANNEL_ALL
        );
    }

    protected function fillAllWithColor(Frame $frame): void
    {
        $draw = new ImagickDraw();
        $draw->setFillColor($this->color->getPixel());
        $draw->rectangle(
            0,
            0,
            $frame->getCore()->getImageWidth(),
            $frame->getCore()->getImageHeight()
        );
        $frame->getCore()->drawImage($draw);
    }

    protected function hasPosition(): bool
    {
        return !empty($this->position);
    }
}
