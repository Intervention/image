<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\Abstract\AbstractTextWriter;
use Intervention\Image\Drivers\Imagick\Font;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ImageInterface;

class TextWriter extends AbstractTextWriter
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $position = $this->getAlignedPosition();
        foreach ($image as $frame) {
            $frame->getCore()->annotateImage(
                $this->getFont()->toImagickDraw(),
                $position->getX(),
                $position->getY(),
                $this->getFont()->getAngle() * (-1),
                $this->text
            );
        }

        return $image;
    }

    protected function getAlignedPosition(): Point
    {
        $position = $this->position;
        $box = $this->getFont()->getBoxSize($this->text);

        // adjust y pos
        switch ($this->getFont()->getValign()) {
            case 'top':
                $position->setY($position->getY() + $box->height());
                break;

            case 'middle':
            case 'center':
                $position->setY(intval($position->getY() + round($box->height() / 2)));
                break;
        }

        return $position;
    }

    private function getFont(): Font
    {
        if (!is_a($this->font, Font::class)) {
            throw new FontException('Font is not compatible to current driver.');
        }
        return $this->font;
    }
}
