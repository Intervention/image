<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPolygonModifier as GenericDrawPolygonModifier;

class DrawPolygonModifier extends GenericDrawPolygonModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        try {
            $drawing = new ImagickDraw();
            $drawing->setFillColor(new ImagickPixel('transparent')); // defaults to no backgroundColor

            if ($this->drawable->hasBackgroundColor()) {
                $backgroundColor = $this->driver()->colorProcessor($image)->colorToNative(
                    $this->backgroundColor()
                );

                $drawing->setFillColor($backgroundColor);
            }

            if ($this->drawable->hasBorder()) {
                $borderColor = $this->driver()->colorProcessor($image)->colorToNative(
                    $this->borderColor()
                );

                $drawing->setStrokeColor($borderColor);
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
     * Return points of drawable in processable form for ImagickDraw.
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
