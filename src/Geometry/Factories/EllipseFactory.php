<?php

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;

class EllipseFactory
{
    protected Ellipse $ellipse;

    public function __construct(protected Point $pivot, callable|Ellipse $init)
    {
        $this->ellipse = is_a($init, Ellipse::class) ? $init : new Ellipse(0, 0, $pivot);

        if (is_callable($init)) {
            $init($this);
        }
    }

    public function size(int $width, int $height): self
    {
        $this->ellipse->setSize($width, $height);

        return $this;
    }

    public function background(mixed $color): self
    {
        $this->ellipse->setBackgroundColor($color);

        return $this;
    }

    public function border(mixed $color, int $size = 1): self
    {
        $this->ellipse->setBorder($color, $size);

        return $this;
    }

    public function __invoke(): Ellipse
    {
        return $this->ellipse;
    }
}
