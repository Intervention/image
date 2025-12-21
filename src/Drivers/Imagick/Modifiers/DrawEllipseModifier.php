<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawEllipseModifier as GenericDrawEllipseModifier;

class DrawEllipseModifier extends GenericDrawEllipseModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $backgroundColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        $borderColor = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->borderColor()
        );

        foreach ($image as $frame) {
            try {
                $drawing = new ImagickDraw();
                $drawing->setFillColor($backgroundColor);

                if ($this->drawable->hasBorder()) {
                    $drawing->setStrokeWidth($this->drawable->borderSize());
                    $drawing->setStrokeColor($borderColor);
                }

                $drawing->ellipse(
                    $this->drawable->position()->x(),
                    $this->drawable->position()->y(),
                    $this->drawable->width() / 2,
                    $this->drawable->height() / 2,
                    0,
                    360
                );
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                    previous: $e
                );
            }

            try {
                $result = $frame->native()->drawImage($drawing);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to draw ellipse on image',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to draw ellipse on image',
                    previous: $e
                );
            }
        }

        return $image;
    }
}
