<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Imagick\FontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property Point $position
 * @property string $text
 * @property FontInterface $font
 */
class TextModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $processor = $this->fontProcessor();
        $lines = $processor->alignedTextBlock($this->position, $this->text);

        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->color())
        );

        $draw = $processor->toImagickDraw($color);

        foreach ($image as $frame) {
            foreach ($lines as $line) {
                $frame->native()->annotateImage(
                    $draw,
                    $line->position()->x(),
                    $line->position()->y(),
                    $this->font->angle(),
                    $line
                );
            }
        }

        return $image;
    }

    private function fontProcessor(): FontProcessor
    {
        return $this->driver()->fontProcessor($this->font);
    }
}
