<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Line;

class LineFactory
{
    protected Line $line;

    /**
     * Create the factory instance
     *
     * @param callable|Line $init
     * @return void
     */
    public function __construct(callable|Line $init)
    {
        $this->line = is_a($init, Line::class) ? $init : new Line(new Point(), new Point());

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * Set the color of the line to be produced
     *
     * @param mixed $color
     * @return LineFactory
     */
    public function color(mixed $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    /**
     * Set the (background) color of the line to be produced
     *
     * @param mixed $color
     * @return LineFactory
     */
    public function background(mixed $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    /**
     * Set the border size & border color of the line to be produced
     *
     * @param mixed $color
     * @param int $size
     * @return LineFactory
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);
        $this->line->setWidth($size);

        return $this;
    }

    /**
     * Set the width of the line to be produced
     *
     * @param int $size
     * @return LineFactory
     */
    public function width(int $size): self
    {
        $this->line->setWidth($size);

        return $this;
    }

    /**
     * Set the coordinates of the starting point of the line to be produced
     *
     * @param int $x
     * @param int $y
     * @return LineFactory
     */
    public function from(int $x, int $y): self
    {
        $this->line->setStart(new Point($x, $y));

        return $this;
    }

    /**
     * Set the coordinates of the end point of the line to be produced
     *
     * @param int $x
     * @param int $y
     * @return LineFactory
     */
    public function to(int $x, int $y): self
    {
        $this->line->setEnd(new Point($x, $y));

        return $this;
    }

    /**
     * Produce the line
     *
     * @return Line
     */
    public function __invoke(): Line
    {
        return $this->line;
    }
}
