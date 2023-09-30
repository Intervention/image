<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawEllipseModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background_color = $this->failIfNotClass($this->getBackgroundColor(), Color::class);
        $border_color = $this->failIfNotClass($this->getBorderColor(), Color::class);

        return $image->eachFrame(function ($frame) use ($background_color, $border_color) {
            $drawing = new ImagickDraw();
            $drawing->setFillColor($background_color->getPixel());

            if ($this->ellipse()->hasBorder()) {
                $drawing->setStrokeWidth($this->ellipse()->getBorderSize());
                $drawing->setStrokeColor($border_color->getPixel());
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
