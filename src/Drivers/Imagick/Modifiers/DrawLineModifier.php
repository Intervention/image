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
use Intervention\Image\Modifiers\DrawLineModifier as GenericDrawLineModifier;

class DrawLineModifier extends GenericDrawLineModifier implements SpecializedInterface
{
    use CanDraw;

    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $line = $this->line(
            $this->driver()->colorProcessor($image)->export($this->backgroundColor()),
        );

        foreach ($image as $frame) {
            $this->draw($frame->native(), $line);
        }

        return $image;
    }

    /**
     * Build drawable line.
     *
     * @throws ModifierException
     */
    private function line(ImagickPixel $color): ImagickDraw
    {
        try {
            $drawing = new ImagickDraw();
            $drawing->setStrokeWidth($this->drawable->width());
            $drawing->setFillOpacity(0);

            if ($this->drawable->hasBackgroundColor()) {
                $drawing->setStrokeColor($color);
            }

            $drawing->line(
                $this->drawable->start()->x(),
                $this->drawable->start()->y(),
                $this->drawable->end()->x(),
                $this->drawable->end()->y(),
            );

            return $drawing;
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e,
            );
        }
    }
}
