<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawRectangleModifier as GenericDrawRectangleModifier;

class DrawRectangleModifier extends GenericDrawRectangleModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $position = $this->drawable->position();

        foreach ($image as $frame) {
            // draw background
            if ($this->drawable->hasBackgroundColor()) {
                $result = imagealphablending($frame->native(), true);

                if ($result === false) {
                    throw new ModifierException('Failed to set alpha blending');
                }

                $result = imagesetthickness($frame->native(), 0);

                if ($result === false) {
                    throw new ModifierException('Failed to set line thickness');
                }

                $result = imagefilledrectangle(
                    $frame->native(),
                    $position->x(),
                    $position->y(),
                    $position->x() + $this->drawable->width(),
                    $position->y() + $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->backgroundColor()
                    )
                );

                if ($result === false) {
                    throw new ModifierException('Failed to draw line on image');
                }
            }

            // draw border
            if ($this->drawable->hasBorder()) {
                $result = imagealphablending($frame->native(), true);

                if ($result === false) {
                    throw new ModifierException('Failed to set alpha blending');
                }

                $result = imagesetthickness($frame->native(), $this->drawable->borderSize());

                if ($result === false) {
                    throw new ModifierException('Failed to set line thickness');
                }
                $result = imagerectangle(
                    $frame->native(),
                    $position->x(),
                    $position->y(),
                    $position->x() + $this->drawable->width(),
                    $position->y() + $this->drawable->height(),
                    $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                        $this->borderColor()
                    )
                );

                if ($result === false) {
                    throw new ModifierException('Failed to draw line on image');
                }
            }
        }

        return $image;
    }
}
