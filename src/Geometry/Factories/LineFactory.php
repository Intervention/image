<?php

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Line;

class LineFactory
{
    protected Line $line;

    public function __construct(callable|Line $init)
    {
        $this->line = is_a($init, Line::class) ? $init : new Line(new Point(), new Point());

        if (is_callable($init)) {
            $init($this);
        }
    }

    public function color(mixed $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    public function background(mixed $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    public function border(mixed $color, int $size = 1): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);
        $this->line->setWidth($size);

        return $this;
    }

    public function width(int $size): self
    {
        $this->line->setWidth($size);

        return $this;
    }

    public function from(int $x, int $y): self
    {
        $this->line->setStart(new Point($x, $y));

        return $this;
    }

    public function to(int $x, int $y): self
    {
        $this->line->setEnd(new Point($x, $y));

        return $this;
    }

    public function __invoke(): Line
    {
        return $this->line;
    }
}
