<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Closure;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class LineFactory implements DrawableFactoryInterface
{
    protected Line $line;

    /**
     * Create the factory instance
     *
     * @return void
     */
    public function __construct(null|Closure|Line $init = null)
    {
        $this->line = is_a($init, Line::class) ? $init : new Line(new Point(), new Point());

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::init()
     */
    public static function init(null|Closure|DrawableInterface $init = null): self
    {
        return new self($init);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::create()
     */
    public function create(): DrawableInterface
    {
        return $this->line;
    }

    /**
     * Set the color of the line to be produced
     */
    public function color(mixed $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    /**
     * Set the (background) color of the line to be produced
     */
    public function background(mixed $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    /**
     * Set the border size & border color of the line to be produced
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
     */
    public function width(int $size): self
    {
        $this->line->setWidth($size);

        return $this;
    }

    /**
     * Set the coordinates of the starting point of the line to be produced
     */
    public function from(int $x, int $y): self
    {
        $this->line->setStart(new Point($x, $y));

        return $this;
    }

    /**
     * Set the coordinates of the end point of the line to be produced
     */
    public function to(int $x, int $y): self
    {
        $this->line->setEnd(new Point($x, $y));

        return $this;
    }

    /**
     * Produce the line
     */
    public function __invoke(): Line
    {
        return $this->line;
    }
}
