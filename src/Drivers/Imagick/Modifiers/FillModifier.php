<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FillModifier as ModifiersFillModifier;

class FillModifier extends ModifiersFillModifier implements SpecializedInterface
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

    private function floodFillWithColor(FrameInterface $frame, ImagickPixel $pixel): void
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

    private function fillAllWithColor(FrameInterface $frame, ImagickPixel $pixel): void
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
