<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Traits\CanDraw;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawPolygonModifier as GenericDrawPolygonModifier;

class DrawPolygonModifier extends GenericDrawPolygonModifier implements SpecializedInterface
{
    use CanDraw;

    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $polygon = $this->polygon(
            $this->driver()->colorProcessor($image)->export($this->backgroundColor()),
            $this->driver()->colorProcessor($image)->export($this->borderColor()),
        );

        foreach ($image as $frame) {
            $this->draw($frame->native(), $polygon);
        }

        return $image;
    }

    /**
     * Create drawable polygon.
     *
     * @throws ModifierException
     */
    private function polygon(ImagickPixel $backgroundColor, ImagickPixel $borderColor): ImagickDraw
    {
        try {
            $polygon = new ImagickDraw();
            $polygon->setFillColor($backgroundColor);

            if ($this->drawable->hasBorder()) {
                $polygon->setStrokeColor($borderColor);
                $polygon->setStrokeWidth($this->drawable->borderSize());
            }

            $polygon->polygon($this->points());

            return $polygon;
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e,
            );
        }
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
