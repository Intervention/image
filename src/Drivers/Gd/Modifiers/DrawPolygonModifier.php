<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPolygonModifier as ModifiersDrawPolygonModifier;

class DrawPolygonModifier extends ModifiersDrawPolygonModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->hasBackgroundColor()) {
                $result = imagealphablending($frame->native(), true);

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set alpha blending',
                    );
                }

                $result = imagesetthickness($frame->native(), 0);

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set line thickness',
                    );
                }

                $result = imagefilledpolygon(
                    $frame->native(),
                    $this->drawable->toArray(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to draw polygon on image',
                    );
                }
            }

            if ($this->drawable->hasBorder()) {
                $result = imagealphablending($frame->native(), true);

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set alpha blending',
                    );
                }

                $result = imagesetthickness($frame->native(), $this->drawable->borderSize());

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set line thickness',
                    );
                }

                $result = imagepolygon(
                    $frame->native(),
                    $this->drawable->toArray(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->borderColor()
                    )
                );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to draw polygon on image',
                    );
                }
            }
        }

        return $image;
    }
}
