<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Drivers\DriverSpecializedModifier;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Geometry\Point;

/**
 * @method bool hasPosition()
 * @property mixed $color
 * @property null|Point $position
 */
class FillModifier extends DriverSpecializedModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->handleInput($this->color);
        $pixel = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($color);

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $pixel);
            } else {
                $this->fillAllWithColor($frame, $pixel);
            }
        }

        return $image;
    }

    private function floodFillWithColor(Frame $frame, ImagickPixel $pixel): void
    {
        $target = $frame->native()->getImagePixelColor(
            $this->position->x(),
            $this->position->y()
        );

        $frame->native()->floodfillPaintImage(
            $pixel,
            100,
            $target,
            $this->position->x(),
            $this->position->y(),
            false,
            Imagick::CHANNEL_ALL
        );
    }

    private function fillAllWithColor(Frame $frame, ImagickPixel $pixel): void
    {
        $draw = new ImagickDraw();
        $draw->setFillColor($pixel);
        $draw->rectangle(
            0,
            0,
            $frame->native()->getImageWidth(),
            $frame->native()->getImageHeight()
        );
        $frame->native()->drawImage($draw);
    }
}
