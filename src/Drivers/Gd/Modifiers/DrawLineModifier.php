<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawLineModifier as GenericDrawLineModifier;

class DrawLineModifier extends GenericDrawLineModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if (!$this->drawable->hasBackgroundColor()) {
            return $image;
        }

        $color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $color);
        }

        return $image;
    }

    /**
     * Draw current line on given frame
     *
     * @throws ModifierException
     */
    private function modifyFrame(FrameInterface $frame, int $color): void
    {
        imagealphablending($frame->native(), true);
        imageantialias($frame->native(), true);
        imagesetthickness($frame->native(), $this->drawable->width());
        imageline(
            $frame->native(),
            $this->drawable->start()->x(),
            $this->drawable->start()->y(),
            $this->drawable->end()->x(),
            $this->drawable->end()->y(),
            $color
        );
    }
}
