<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->getAlignedTextBlock();
        $font = $this->failIfNotClass($this->getFont(), Font::class);

        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    imagettftext(
                        $frame->getCore(),
                        $font->getSize(),
                        $font->getAngle() * (-1),
                        $line->getPosition()->getX(),
                        $line->getPosition()->getY(),
                        $font->getColor()->toInt(),
                        $font->getFilename(),
                        $line
                    );
                }
            } else {
                foreach ($lines as $line) {
                    imagestring(
                        $frame->getCore(),
                        $font->getGdFont(),
                        $line->getPosition()->getX(),
                        $line->getPosition()->getY(),
                        $line,
                        $this->font->getColor()->toInt()
                    );
                }
            }
        }

        return $image;
    }
}
