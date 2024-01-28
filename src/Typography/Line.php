<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\PointInterface;

class Line
{
    /**
     * Create new text line object with given text & position
     *
     * @param string $text
     * @param PointInterface $position
     * @return void
     */
    public function __construct(
        protected string $text,
        protected PointInterface $position = new Point()
    ) {
    }

    /**
     * Get Position of line
     *
     * @return PointInterface
     */
    public function position(): PointInterface
    {
        return $this->position;
    }

    /**
     * Set position of current line
     *
     * @param Point $point
     * @return Line
     */
    public function setPosition(Point $point): self
    {
        $this->position = $point;

        return $this;
    }

    /**
     * Cast line to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->text;
    }
}
