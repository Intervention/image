<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPolygonModifier as GenericDrawPolygonModifier;

class DrawPolygonModifier extends GenericDrawPolygonModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        try {
            $drawing = new ImagickDraw();
            $drawing->setFillColor(new ImagickPixel('transparent')); // defaults to no backgroundColor

            if ($this->drawable->hasBackgroundColor()) {
                $background_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->backgroundColor()
                );

                $drawing->setFillColor($background_color);
            }

            if ($this->drawable->hasBorder()) {
                $border_color = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
                    $this->borderColor()
                );

                $drawing->setStrokeColor($border_color);
                $drawing->setStrokeWidth($this->drawable->borderSize());
            }

            $drawing->polygon($this->points());
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
                        'Failed to apply ' . self::class . ', unable to draw polygon on image',
                    );
                }
            } catch (ImagickException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to draw polygon on image',
                    previous: $e
                );
            }
        }

        return $image;
    }

    /**
     * Return points of drawable in processable form for ImagickDraw
     *
     * @return array<array<string, int>>
     */
    private function points(): array
    {
        $points = [];
        foreach ($this->drawable as $point) {
            $points[] = ['x' => $point->x(), 'y' => $point->y()];
        }

        return $points;
    }
}
