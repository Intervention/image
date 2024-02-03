<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\PointInterface;

class Line
{
    /**
     * Segments (usually individual words) of the line
     */
    protected array $segments = [];

    /**
     * Create new text line object with given text & position
     *
     * @param string $text
     * @param PointInterface $position
     * @return void
     */
    public function __construct(
        string $text,
        protected PointInterface $position = new Point()
    ) {
        $this->segments = explode(" ", $text);
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
     * Count segments of line
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->segments);
    }

    /**
     * Cast line to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return implode(" ", $this->segments);
    }
}
