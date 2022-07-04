<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Imagick\Font;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $lines = $this->getAlignedTextBlock();
        foreach ($image as $frame) {
            foreach ($lines as $line) {
                $frame->getCore()->annotateImage(
                    $this->getFont()->toImagickDraw(),
                    $line->getPosition()->getX(),
                    $line->getPosition()->getY(),
                    $this->getFont()->getAngle(),
                    $line
                );
            }
        }

        return $image;
    }

    // protected function getAlignedPosition(): Point
    // {
    //     $position = $this->position;
    //     $box = $this->getFont()->getBoxSize($this->text);
    //
    //     // adjust y pos
    //     switch ($this->getFont()->getValign()) {
    //         case 'top':
    //             $position->setY($position->getY() + $box->height());
    //             break;
    //
    //         case 'middle':
    //         case 'center':
    //             $position->setY(intval($position->getY() + round($box->height() / 2)));
    //             break;
    //     }
    //
    //     return $position;
    // }

    protected function getFont(): FontInterface
    {
        if (!is_a($this->font, Font::class)) {
            throw new FontException('Font is not compatible to current driver.');
        }
        return $this->font;
    }
}
