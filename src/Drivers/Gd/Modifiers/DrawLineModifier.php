<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawLineModifier as GenericDrawLineModifier;

class DrawLineModifier extends GenericDrawLineModifier implements SpecializedInterface
{
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
     */
    private function modifyFrame(FrameInterface $frame, int $color): void
    {
        $result = imagealphablending($frame->native(), true);

        if ($result === false) {
            throw new ModifierException('Failed to set alpha blending');
        }

        $result = imageantialias($frame->native(), true);

        if ($result === false) {
            throw new ModifierException('Failed to set antialias option on image');
        }

        $result = imagesetthickness($frame->native(), $this->drawable->width());

        if ($result === false) {
            throw new ModifierException('Failed to set line thickness');
        }

        $result = imageline(
            $frame->native(),
            $this->drawable->start()->x(),
            $this->drawable->start()->y(),
            $this->drawable->end()->x(),
            $this->drawable->end()->y(),
            $color
        );

        if ($result === false) {
            throw new ModifierException('Failed to draw line on image');
        }
    }
}
