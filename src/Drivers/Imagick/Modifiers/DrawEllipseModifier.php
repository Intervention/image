<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawEllipseModifier as GenericDrawEllipseModifier;

class DrawEllipseModifier extends GenericDrawEllipseModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $drawing = $this->buildDrawing($image);

        foreach ($image as $frame) {
            try {
                $result = $frame->native()->drawImage($drawing);
                if ($result === false) {
                    throw new ModifierException(
                        'Failed to apply ' . self::class . ', unable to draw ellipse on image',
                    );
                }
            } catch (ImagickException | ImagickDrawException $e) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to draw ellipse on image',
                    previous: $e
                );
            }
        }

        return $image;
    }

    /**
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    private function buildDrawing(ImageInterface $image): ImagickDraw
    {
        try {
            $drawing = new ImagickDraw();

            if ($this->drawable->hasBackgroundColor()) {
                $backgroundColor = $this->driver()->colorProcessor($image)->export(
                    $this->backgroundColor()
                );

                $drawing->setFillColor($backgroundColor);
            }

            if ($this->drawable->hasBorder()) {
                $borderColor = $this->driver()->colorProcessor($image)->export(
                    $this->borderColor()
                );

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
        } catch (ImagickException | ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e
            );
        }

        return $drawing;
    }
}
