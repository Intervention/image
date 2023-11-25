<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\DriverModifier;
use Intervention\Image\Drivers\Gd\FontProcessor;
use Intervention\Image\Interfaces\ImageInterface;

class TextModifier extends DriverModifier
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $processor = $this->fontProcessor();
        $lines = $processor->getAlignedTextBlock($this->position, $this->text);

        $color = $this->driver()->colorToNative(
            $this->driver()->handleInput($this->font->color()),
            new Colorspace()
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
                        $line->getPosition()->x(),
                        $line->getPosition()->y(),
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
