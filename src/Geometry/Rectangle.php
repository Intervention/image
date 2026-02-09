<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use ArrayIterator;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Interfaces\DrawableFactoryInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;
use Traversable;

class Rectangle extends Size implements SizeInterface
{
    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setPosition()
     */
    public function setPosition(PointInterface $position): self
    {
        parent::setPosition($position);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->points);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::factory()
     */
    public function factory(): DrawableFactoryInterface
    {
        return new RectangleFactory($this);
    }
}
