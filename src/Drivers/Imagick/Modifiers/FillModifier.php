<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class FillModifier implements ModifierInterface
{
    use CanHandleColors;

    public function __construct(
        protected ColorInterface $color,
        protected ?Point $position = null
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $pixel = $this->colorToPixel($this->color, $image->getColorspace());

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $pixel);
            } else {
                $this->fillAllWithColor($frame, $pixel);
            }
        }

        return $image;
    }

    protected function floodFillWithColor(Frame $frame, ImagickPixel $pixel): void
    {
        $target = $frame->getCore()->getImagePixelColor(
            $this->position->getX(),
            $this->position->getY()
        );

        $frame->getCore()->floodfillPaintImage(
            $pixel,
            100,
            $target,
            $this->position->getX(),
            $this->position->getY(),
            false,
            Imagick::CHANNEL_ALL
        );
    }

    protected function fillAllWithColor(Frame $frame, ImagickPixel $pixel): void
    {
        $draw = new ImagickDraw();
        $draw->setFillColor($pixel);
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
