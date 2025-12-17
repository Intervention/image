<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
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
            $this->driver()->handleColorInput($this->color)
        );

        foreach ($image as $frame) {
            $result = imagealphablending($frame->native(), true);

            if ($result === false) {
                throw new ModifierException('Failed to set alpha blending');
            }

            $result = imagesetpixel(
                $frame->native(),
                $this->position->x(),
                $this->position->y(),
                $color
            );

            if ($result === false) {
                throw new ModifierException('Failed to draw pixel on image');
            }
        }

        return $image;
    }
}
