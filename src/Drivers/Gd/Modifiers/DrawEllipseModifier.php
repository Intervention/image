<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawEllipseModifier as GenericDrawEllipseModifier;

class DrawEllipseModifier extends GenericDrawEllipseModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->hasBorder()) {
                $result = imagealphablending($frame->native(), true);

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set alpha blending',
                    );
                }

                // slightly smaller ellipse to keep 1px bordered edges clean
                if ($this->drawable->hasBackgroundColor()) {
                    $result = imagefilledellipse(
                        $frame->native(),
                        $this->drawable()->position()->x(),
                        $this->drawable->position()->y(),
                        $this->drawable->width() - 1,
                        $this->drawable->height() - 1,
                        $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                            $this->backgroundColor()
                        )
                    );

                    if ($result === false) {
                        throw new ModifierException(
                            'Failed to apply ' . self::class . ', unable to draw ellipse on image',
                        );
                    }
                }

                // gd's imageellipse ignores imagesetthickness
                // so i use imagearc with 360 degrees instead.
                $result = imagesetthickness(
                    $frame->native(),
                    $this->drawable->borderSize(),
                );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to set line thickness',
                    );
                }

                $result = imagearc(
                    $frame->native(),
                    $this->drawable()->position()->x(),
                    $this->drawable()->position()->y(),
                    $this->drawable->width(),
                    $this->drawable->height(),
                    0,
                    360,
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->borderColor()
                    )
                );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to draw ellipse on image',
                    );
                }
            } elseif ($this->drawable->hasBackgroundColor()) {
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

                $result = imagefilledellipse(
                    $frame->native(),
                    $this->drawable()->position()->x(),
                    $this->drawable()->position()->y(),
                    $this->drawable->width(),
                    $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );

                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to draw ellipse on image',
                    );
                }
            }
        }

        return $image;
    }
}
