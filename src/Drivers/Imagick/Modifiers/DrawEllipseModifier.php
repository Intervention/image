<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Imagick\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawEllipseModifier extends AbstractDrawModifier implements ModifierInterface
{
    use CanHandleColors;

    public function apply(ImageInterface $image): ImageInterface
    {
        $colorspace = $image->colorspace();
        $background_color = $this->colorToPixel($this->getBackgroundColor(), $colorspace);
        $border_color = $this->colorToPixel($this->getBorderColor(), $colorspace);

        return $image->mapFrames(function ($frame) use ($background_color, $border_color) {
            $drawing = new ImagickDraw();
            $drawing->setFillColor($background_color);

            if ($this->ellipse()->hasBorder()) {
                $drawing->setStrokeWidth($this->ellipse()->getBorderSize());
                $drawing->setStrokeColor($border_color);
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
}
