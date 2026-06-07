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
use Intervention\Image\Modifiers\DrawEllipseModifier as GenericDrawEllipseModifier;

class DrawEllipseModifier extends GenericDrawEllipseModifier implements SpecializedInterface
{
    use CanDraw;

    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $ellipse = $this->ellipse(
            $this->driver()->colorProcessor($image)->export($this->backgroundColor()),
            $this->driver()->colorProcessor($image)->export($this->borderColor()),
        );

        foreach ($image as $frame) {
            $this->draw($frame->native(), $ellipse);
        }

        return $image;
    }

    /**
     * Build drawable ellipse in given colors.
     *
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    private function ellipse(ImagickPixel $backgroundColor, ImagickPixel $borderColor): ImagickDraw
    {
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
                360,
            );
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to draw ellipse',
                previous: $e,
            );
        }

        return $drawing;
    }
}
