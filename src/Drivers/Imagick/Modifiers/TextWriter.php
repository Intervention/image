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
            
            // debug
            // $lines = new TextBlock($this->text);
            // $box = $lines->getBoundingBox($this->font, $this->position);
            // $points = [];
            // foreach (array_chunk($box->toArray(), 2) as $p) {
            //     $points[] = ['x' => $p[0], 'y' => $p[1]];
            // }
            // $draw = new \ImagickDraw();
            // $draw->setStrokeOpacity(1);
            // $draw->setStrokeColor('black');
            // $draw->setFillColor('transparent');
            // $draw->polygon($points);
            // $frame->getCore()->drawImage($draw);

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
