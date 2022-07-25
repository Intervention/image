<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawEllipseModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->eachFrame(function ($frame) {
            $drawing = new ImagickDraw();
            $drawing->setFillColor($this->getBackgroundColor()->getPixel());

            if ($this->ellipse()->hasBorder()) {
                $drawing->setStrokeWidth($this->ellipse()->getBorderSize());
                $drawing->setStrokeColor($this->getBorderColor()->getPixel());
            }

            $drawing->ellipse(
                $this->position->getX(),
                $this->position->getY(),
                $this->ellipse()->getWidth() / 2,
                $this->ellipse()->getHeight() / 2,
                0,
                360
            );

            $frame->getCore()->drawImage($drawing);
        });
    }

    protected function getBackgroundColor(): ColorInterface
    {
        $color = parent::getBackgroundColor();
        if (!is_a($color, Color::class)) {
            throw new DecoderException('Unable to decode background color.');
        }

        return $color;
    }

    protected function getBorderColor(): ColorInterface
    {
        $color = parent::getBorderColor();
        if (!is_a($color, Color::class)) {
            throw new DecoderException('Unable to decode border color.');
        }

        return $color;
    }
}
