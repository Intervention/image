<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPolygonModifier as ModifiersDrawPolygonModifier;

class DrawPolygonModifier extends ModifiersDrawPolygonModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->hasBackgroundColor()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), 0);
                imagefilledpolygon(
                    $frame->native(),
                    $this->drawable->toArray(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );
            }

            if ($this->drawable->hasBorder()) {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), $this->drawable->borderSize());
                imagepolygon(
                    $frame->native(),
                    $this->drawable->toArray(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->borderColor()
                    )
                );
            }
        }

        return $image;
    }
}
