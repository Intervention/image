<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;
use Intervention\Image\Interfaces\PointInterface;

class LineFactory implements DrawableFactoryInterface
{
    protected Line $line;
    protected PointInterface $position;

    /**
     * Create the factory instance.
     */
    public function __construct(null|callable|Line $line = null)
    {
        $this->position = new Point();
        $this->line = is_a($line, Line::class) ? $line : new Line($this->position, $this->position);

        if (is_callable($line)) {
            $line($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(null|callable|DrawableInterface $drawable = null): Line
    {
        return (new self($drawable))->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): Line
    {
        $delta = new Point(
            $this->position->x() - $this->line->start()->x(),
            $this->position->y() - $this->line->start()->y(),
        );

        $this->line->start()->move(...$delta);
        $this->line->end()->move(...$delta);

        return $this->line;
    }

    /**
     * Set the color of the line to be produced.
     */
    public function color(string|ColorInterface $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    /**
     * Set the (background) color of the line to be produced.
     */
    public function background(string|ColorInterface $color): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);

        return $this;
    }

    /**
     * Set the border size & border color of the line to be produced.
     */
    public function border(string|ColorInterface $color, int $size = 1): self
    {
        $this->line->setBackgroundColor($color);
        $this->line->setBorderColor($color);
        $this->line->setWidth($size);

        return $this;
    }

    /**
     * Set the width of the line to be produced.
     */
    public function width(int $size): self
    {
        $this->line->setWidth($size);

        return $this;
    }

    /**
     * Set the coordinates of the starting point of the line to be produced.
     */
    public function from(int $x, int $y): self
    {
        $this->line->setStart(new Point($x, $y));

        return $this;
    }

    /**
     * Set the coordinates of the end point of the line to be produced.
     */
    public function to(int $x, int $y): self
    {
        $this->line->setEnd(new Point($x, $y));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::at()
     */
    public function at(int $x, int $y): DrawableFactoryInterface
    {
        $this->position = new Point($x, $y);

        return $this;
    }
}
