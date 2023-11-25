<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Gd\FontProcessor;
use Intervention\Image\Interfaces\ImageInterface;

class TextModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $processor = $this->fontProcessor();
        $lines = $processor->alignedTextBlock($this->position, $this->text);

        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->font->color())
        );

        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    imagettftext(
                        $frame->native(),
                        $processor->adjustedSize(),
                        $this->font->angle() * -1,
                        $line->position()->x(),
                        $line->position()->y(),
                        $color,
                        $this->font->filename(),
                        $line
                    );
                }
            } else {
                foreach ($lines as $line) {
                    imagestring(
                        $frame->native(),
                        $processor->getGdFont(),
                        $line->position()->x(),
                        $line->position()->y(),
                        $line,
                        $color
                    );
                }
            }
        }

        return $image;
    }

    private function fontProcessor(): FontProcessor
    {
        return $this->driver()->fontProcessor($this->font);
    }
}
