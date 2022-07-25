<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Traits\CanHandleInput;

class AbstractDrawModifier
{
    use CanHandleInput;

    public function __construct(
        protected PointInterface $position,
        protected DrawableInterface $drawable
    ) {
        //
    }

    public function drawable(): DrawableInterface
    {
        return $this->drawable;
    }

    protected function getBackgroundColor(): ?ColorInterface
    {
        try {
            $color = $this->handleInput($this->drawable->getBackgroundColor());
        } catch (DecoderException $e) {
            return $this->handleInput('transparent');
        }

        return $color;
    }

    protected function getBorderColor(): ?ColorInterface
    {
        try {
            $color = $this->handleInput($this->drawable->getBorderColor());
        } catch (DecoderException $e) {
            return $this->handleInput('transparent');
        }

        return $color;
    }

    public function polygon(): Polygon
    {
        if (!is_a($this->drawable(), Polygon::class)) {
            throw new GeometryException(
                'Shape mismatch. Excepted Polygon::class, found ' . get_class($this->drawable())
            );
        }

        return $this->drawable();
    }

    public function ellipse(): Ellipse
    {
        if (!is_a($this->drawable(), Ellipse::class)) {
            throw new GeometryException(
                'Shape mismatch. Excepted Ellipse::class, found ' . get_class($this->drawable())
            );
        }

        return $this->drawable();
    }

    public function line(): Line
    {
        if (!is_a($this->drawable(), Line::class)) {
            throw new GeometryException(
                'Shape mismatch. Excepted Line::class, found ' . get_class($this->drawable())
            );
        }

        return $this->drawable();
    }

    public function rectangle(): Rectangle
    {
        if (!is_a($this->drawable(), Rectangle::class)) {
            throw new GeometryException(
                'Shape mismatch. Excepted Rectangle::class, found ' . get_class($this->drawable())
            );
        }

        return $this->drawable();
    }
}
