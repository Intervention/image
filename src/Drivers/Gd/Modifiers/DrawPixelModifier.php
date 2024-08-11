<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPixelModifier as GenericDrawPixelModifier;

class DrawPixelModifier extends GenericDrawPixelModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->color)
        );

        foreach ($image as $frame) {
            imagealphablending($frame->native(), true);
            imagesetpixel(
                $frame->native(),
                $this->position->x(),
                $this->position->y(),
                $color
            );
        }

        return $image;
    }
}
