<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Font;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class TextWriter implements ModifierInterface
{
    public function __construct(
        protected Point $position,
        protected Font $font
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $position = $this->getAlignedPosition();

        foreach ($image as $frame) {
            if ($this->font->hasFilename()) {
                imagettftext(
                    $frame->getCore(),
                    $this->font->getSize(),
                    $this->font->getAngle(),
                    $position->getX(),
                    $position->getY(),
                    $this->font->getColor()->toInt(),
                    $this->font->getFilename(),
                    $this->font->getText()
                );
            } else {
                imagestring(
                    $frame->getCore(),
                    $this->font->getGdFont(),
                    $position->getX(),
                    $position->getY(),
                    $this->font->getText(),
                    $this->font->getColor()->toInt()
                );
            }
        }

        return $image;
    }

    protected function getAlignedPosition(): Point
    {
        $position = $this->position;
        $box = $this->font->getBoxSize();

        // adjust x pos
        switch ($this->font->getAlign()) {
            case 'center':
                $position->setX($position->getX() - round($box->getWidth() / 2));
                break;

            case 'right':
                $position->setX($position->getX() - $box->getWidth());
                break;
        }

        // adjust y pos
        switch ($this->font->getValign()) {
            case 'top':
                $position->setY($position->getY() + $box->getHeight());
                break;

            case 'middle':
            case 'center':
                $position->setY($position->getY() + round($box->getHeight() / 2));
                break;
        }

        return $position;
    }
}
