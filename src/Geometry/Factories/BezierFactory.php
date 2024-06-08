<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Bezier;

class BezierFactory
{
    protected Bezier $bezier;

    /**
     * Create new factory instance
     *
     * @param callable|Bezier $init
     * @return void
     */
    public function __construct(callable|Bezier $init)
    {
        $this->bezier = is_a($init, Bezier::class) ? $init : new Bezier([]);

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * Add a point to the bezier to be produced
     *
     * @param int $x
     * @param int $y
     * @return BezierFactory
     */
    public function point(int $x, int $y): self
    {
        $this->bezier->addPoint(new Point($x, $y));

        return $this;
    }

    /**
     * Set the background color of the bezier to be produced
     *
     * @param mixed $color
     * @return BezierFactory
     */
    public function background(mixed $color): self
    {
        $this->bezier->setBackgroundColor($color);

        return $this;
    }

    /**
     * Set the border color & border size of the bezier to be produced
     *
     * @param mixed $color
     * @param int $size
     * @return BezierFactory
     */
    public function border(mixed $color, int $size = 1): self
    {
        $this->bezier->setBorder($color, $size);

        return $this;
    }

    /**
     * Produce the bezier
     *
     * @return Bezier
     */
    public function __invoke(): Bezier
    {
        return $this->bezier;
    }
}
