<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickDrawException;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Traits\CanDraw;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawBezierModifier as GenericDrawBezierModifier;

class DrawBezierModifier extends GenericDrawBezierModifier implements SpecializedInterface
{
    use CanDraw;

    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     * @throws ColorDecoderException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $bezierCurve = $this->bezierCurve(
            $this->driver()->colorProcessor($image)->export($this->backgroundColor()),
            $this->driver()->colorProcessor($image)->export($this->borderColor()),
        );

        foreach ($image as $frame) {
            $this->draw($frame->native(), $bezierCurve);
        }

        return $image;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     */
    private function bezierCurve(ImagickPixel $backgroundColor, ImagickPixel $borderColor): ImagickDraw
    {
        try {
            $bezierCurve = new ImagickDraw();
            $bezierCurve->setFillColor($backgroundColor);
        } catch (ImagickDrawException $e) {
            throw new ModifierException(
                'Failed to build bezier curve',
                previous: $e,
            );
        }

        if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 0) {
            try {
                $bezierCurve->setStrokeColor($borderColor);
                $bezierCurve->setStrokeWidth($this->drawable->borderSize());
            } catch (ImagickDrawException $e) {
                throw new ModifierException(
                    'Failed to build bezier curve',
                    previous: $e,
                );
            }
        }

        $bezierCurve->pathStart();
        $bezierCurve->pathMoveToAbsolute(
            $this->drawable->first()->x(),
            $this->drawable->first()->y()
        );

        match ($this->drawable->count()) {
            3 => $bezierCurve->pathCurveToQuadraticBezierAbsolute(
                $this->drawable->second()->x(),
                $this->drawable->second()->y(),
                $this->drawable->last()->x(),
                $this->drawable->last()->y()
            ),
            4 => $bezierCurve->pathCurveToAbsolute(
                $this->drawable->second()->x(),
                $this->drawable->second()->y(),
                $this->drawable->third()->x(),
                $this->drawable->third()->y(),
                $this->drawable->last()->x(),
                $this->drawable->last()->y()
            ),
            default => throw new InvalidArgumentException(
                'You must specify either 3 or 4 points to create a bezier curve',
            ),
        };

        $bezierCurve->pathFinish();

        return $bezierCurve;
    }
}
