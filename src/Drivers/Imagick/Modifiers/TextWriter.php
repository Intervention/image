<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Imagick\Font;
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
        $draw = $this->font->toImagickDraw();
        $position = $this->getAlignedPosition();

        foreach ($image as $frame) {
            $frame->getCore()->annotateImage(
                $draw,
                $position->getX(),
                $position->getY(),
                $this->font->getAngle(),
                $this->font->getText()
            );
        }

        return $image;
    }

    protected function getAlignedPosition(): Point
    {
        $position = $this->position;
        $box = $this->font->getBoxSize();

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
