<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Factories;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\DrawableInterface;

class LineFactory implements DrawableFactoryInterface
{
    protected Line $line;

    /**
     * Create the factory instance.
     */
    public function __construct(null|callable|Line $line = null)
    {
        $this->line = $line instanceof Line ? clone $line : new Line(new Point(), new Point());

        if (is_callable($line)) {
            $line($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::build()
     */
    public static function build(
        null|callable|DrawableInterface $drawable = null,
        ?callable $adjustments = null,
    ): Line {
        $factory = new self($drawable);

        if (is_callable($adjustments)) {
            $adjustments($factory);
        }

        return $factory->drawable();
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableFactoryInterface::drawable()
     */
    public function drawable(): Line
    {
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
}
