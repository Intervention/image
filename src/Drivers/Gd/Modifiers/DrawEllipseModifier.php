<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawEllipseModifier as GenericDrawEllipseModifier;

class DrawEllipseModifier extends GenericDrawEllipseModifier implements SpecializedInterface
{
    /**
     * @throws RuntimeException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            if ($this->drawable->hasBorder()) {
                imagealphablending($frame->native(), true);

                // slightly smaller ellipse to keep 1px bordered edges clean
                if ($this->drawable->hasBackgroundColor()) {
                    imagefilledellipse(
                        $frame->native(),
                        $this->drawable()->position()->x(),
                        $this->drawable->position()->y(),
                        $this->drawable->width() - 1,
                        $this->drawable->height() - 1,
                        $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                            $this->backgroundColor()
                        )
                    );
                }

                // gd's imageellipse ignores imagesetthickness
                // so i use imagearc with 360 degrees instead.
                imagesetthickness(
                    $frame->native(),
                    $this->drawable->borderSize(),
                );

                imagearc(
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
            } else {
                imagealphablending($frame->native(), true);
                imagesetthickness($frame->native(), 0);
                imagefilledellipse(
                    $frame->native(),
                    $this->drawable()->position()->x(),
                    $this->drawable()->position()->y(),
                    $this->drawable->width(),
                    $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );
            }
        }

        return $image;
    }
}
