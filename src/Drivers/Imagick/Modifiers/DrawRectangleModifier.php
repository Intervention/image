<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawRectangleModifier as GenericDrawRectangleModifier;

class DrawRectangleModifier extends GenericDrawRectangleModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = new ImagickDraw();

        $backgroundColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        try {
            $drawing->setFillColor($backgroundColor);
            if ($this->drawable->hasBorder()) {
                $borderColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->borderColor()
                );

                $drawing->setStrokeColor($borderColor);
                $drawing->setStrokeWidth($this->drawable->borderSize());
            }

            // build rectangle
            $drawing->rectangle(
                $this->drawable->position()->x(),
                $this->drawable->position()->y(),
                $this->drawable->position()->x() + $this->drawable->width(),
                $this->drawable->position()->y() + $this->drawable->height()
            );
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e
            );
        }

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->drawImage($drawing);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to draw rectangle on image',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to draw rectangle on image',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
