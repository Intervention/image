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
use Intervention\Image\Modifiers\DrawRectangleModifier as GenericDrawRectangleModifier;

class DrawRectangleModifier extends GenericDrawRectangleModifier implements SpecializedInterface
{
    use CanDraw;

    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $rectangle = $this->rectangle(
            $this->driver()->colorProcessor($image)->export($this->backgroundColor()),
            $this->driver()->colorProcessor($image)->export($this->borderColor()),
        );

        foreach ($image as $frame) {
            $this->draw($frame->native(), $rectangle);
        }

        return $image;
    }

    /**
     * Build drawable rectangle.
     *
     * @throws ModifierException
     */
    private function rectangle(ImagickPixel $backgroundColor, ImagickPixel $borderColor): ImagickDraw
    {
        try {
            $drawing = new ImagickDraw();
            $drawing->setFillColor($backgroundColor);

            if ($this->drawable->hasBorder()) {
                $drawing->setStrokeColor($borderColor);
                $drawing->setStrokeWidth($this->drawable->borderSize());
            }

            $drawing->rectangle(
                $this->drawable->position()->x(),
                $this->drawable->position()->y(),
                $this->drawable->position()->x() + $this->drawable->width(),
                $this->drawable->position()->y() + $this->drawable->height()
            );

            return $drawing;
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e
            );
        }
    }
}
