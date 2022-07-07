<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawPixelModifier implements ModifierInterface
{
    public function __construct(protected Point $position, protected ColorInterface $color)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $pixel = new ImagickDraw();
        $pixel->setFillColor($this->getColor()->getPixel());
        $pixel->point($this->position->getX(), $this->position->getY());

        foreach ($image as $frame) {
            $frame->getCore()->drawImage($pixel);
        }

        return $image;
    }

    public function getColor(): Color
    {
        if (!is_a($this->color, Color::class)) {
            throw new DecoderException('Unable to decode given pixel color.');
        }

        return $this->color;
    }
}
