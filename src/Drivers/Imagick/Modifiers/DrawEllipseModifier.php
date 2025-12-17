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
        $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->backgroundColor()
        );

        $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->borderColor()
        );

        foreach ($image as $frame) {
            try {
                $drawing = new ImagickDraw();
                $drawing->setFillColor($background_color);

                if ($this->drawable->hasBorder()) {
                    $drawing->setStrokeWidth($this->drawable->borderSize());
                    $drawing->setStrokeColor($border_color);
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
