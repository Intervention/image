<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawBezierModifier as GenericDrawBezierModifier;

class DrawBezierModifier extends GenericDrawBezierModifier implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($this->drawable->count() !== 3 && $this->drawable->count() !== 4) {
            throw new InvalidArgumentException('You must specify either 3 or 4 points to create a bezier curve');
        }

        $drawing = new ImagickDraw();

        if ($this->drawable->hasBackgroundColor()) {
            $backgroundColor = $this->driver()->colorProcessor($image)->colorToNative(
                $this->backgroundColor()
            );
        } else {
            $backgroundColor = 'transparent';
        }

        $drawing->setFillColor($backgroundColor);

        if ($this->drawable->hasBorder() && $this->drawable->borderSize() > 0) {
            $borderColor = $this->driver()->colorProcessor($image)->colorToNative(
                $this->borderColor()
            );

            $drawing->setStrokeColor($borderColor);
            $drawing->setStrokeWidth($this->drawable->borderSize());
        }

        $drawing->pathStart();
        $drawing->pathMoveToAbsolute(
            $this->drawable->position()->x() + $this->drawable->first()->x(),
            $this->drawable->position()->y() + $this->drawable->first()->y()
        );
        if ($this->drawable->count() === 3) {
            $drawing->pathCurveToQuadraticBezierAbsolute(
                $this->drawable->position()->x() + $this->drawable->second()->x(),
                $this->drawable->position()->y() + $this->drawable->second()->y(),
                $this->drawable->position()->x() + $this->drawable->last()->x(),
                $this->drawable->position()->y() + $this->drawable->last()->y()
            );
        } elseif ($this->drawable->count() === 4) {
            $drawing->pathCurveToAbsolute(
                $this->drawable->position()->x() + $this->drawable->second()->x(),
                $this->drawable->position()->x() + $this->drawable->second()->y(),
                $this->drawable->position()->x() + $this->drawable->third()->x(),
                $this->drawable->position()->x() + $this->drawable->third()->y(),
                $this->drawable->position()->x() + $this->drawable->last()->x(),
                $this->drawable->position()->x() + $this->drawable->last()->y()
            );
        }
        $drawing->pathFinish();

        foreach ($image as $frame) {
            $result = $frame->native()->drawImage($drawing);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to draw bezier curve',
                );
            }
        }

        return $image;
    }
}
