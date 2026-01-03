<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;

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
}
