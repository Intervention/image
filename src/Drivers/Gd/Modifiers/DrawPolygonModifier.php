<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Abstract\Modifiers\AbstractDrawModifier;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class DrawPolygonModifier extends AbstractDrawModifier implements ModifierInterface
{
    public function __construct(
        protected DrawableInterface $drawable
    ) {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        return $image->eachFrame(function ($frame) {
            if ($this->polygon()->hasBackgroundColor()) {
                imagefilledpolygon(
                    $frame->getCore(),
                    $this->polygon()->toArray(),
                    $this->polygon()->count(),
                    $this->getBackgroundColor()->toInt()
                );
            }

            if ($this->polygon()->hasBorder()) {
                imagesetthickness($frame->getCore(), $this->polygon()->getBorderSize());
                imagepolygon(
                    $frame->getCore(),
                    $this->polygon()->toArray(),
                    $this->polygon()->count(),
                    $this->getBorderColor()->toInt()
                );
            }
        });
    }
}
