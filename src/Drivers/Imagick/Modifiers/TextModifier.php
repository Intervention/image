<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Imagick\FontProcessor;
use Intervention\Image\Interfaces\ImageInterface;

class TextModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $processor = $this->fontProcessor();
        $lines = $processor->getAlignedTextBlock($this->position, $this->text);

        $color = $this->driver()->colorToNative(
            $this->driver()->handleInput($this->font->color()),
            $image->colorspace()
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
