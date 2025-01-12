<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FillModifier as ModifiersFillModifier;

class FillModifier extends ModifiersFillModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $pixel = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->color)
        );

        foreach ($image->core()->native() as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $pixel);
            } else {
                $this->fillAllWithColor($frame, $pixel);
            }
        }

        return $image;
    }

    private function floodFillWithColor(Imagick $frame, ImagickPixel $pixel): void
    {
        $target = $frame->getImagePixelColor(
            $this->position->x(),
            $this->position->y()
        );

        $frame->floodfillPaintImage(
            $pixel,
            100,
            $target,
            $this->position->x(),
            $this->position->y(),
            false,
            Imagick::CHANNEL_ALL
        );
    }

    private function fillAllWithColor(Imagick $frame, ImagickPixel $pixel): void
    {
        $draw = new ImagickDraw();
        $draw->setFillColor($pixel);
        $draw->rectangle(0, 0, $frame->getImageWidth(), $frame->getImageHeight());
        $frame->drawImage($draw);

        // deactive alpha channel when image was filled with opaque color
        if ($pixel->getColorValue(Imagick::COLOR_ALPHA) == 1) {
            $frame->setImageAlphaChannel(Imagick::ALPHACHANNEL_DEACTIVATE);
        }
    }
}
