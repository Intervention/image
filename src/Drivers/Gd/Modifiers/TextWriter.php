<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->getAlignedTextBlock();
        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                foreach ($lines as $line) {
                    imagettftext(
                        $frame->getCore(),
                        $this->getFont()->getSize(),
                        $this->getFont()->getAngle() * (-1),
                        $line->getPosition()->getX(),
                        $line->getPosition()->getY(),
                        $this->getFont()->getColor()->toInt(),
                        $this->getFont()->getFilename(),
                        $line
                    );
                }

                // debug
                // $lines = new TextBlock($this->text);
                // $box = $lines->getBoundingBox($this->font, $this->position);
                // imagepolygon($frame->getCore(), $box->toArray(), 0);
            } else {
                foreach ($lines as $line) {
                    imagestring(
                        $frame->getCore(),
                        $this->getFont()->getGdFont(),
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

    protected function getFont(): FontInterface
    {
        if (!is_a($this->font, Font::class)) {
            throw new FontException('Font is not compatible to current driver.');
        }
        return $this->font;
    }
}
