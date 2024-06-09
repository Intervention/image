<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use ArrayIterator;
use Countable;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\PointInterface;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<string>
 */
class Line implements IteratorAggregate, Countable
{
    /**
     * Segments (usually individual words including punctuation marks) of the line
     *
     * @var array<string>
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
        ?string $text = null,
        protected PointInterface $position = new Point()
    ) {
        if (is_string($text)) {
            $this->segments = explode(" ", $text);
        }
    }

    /**
     * Add word to current line
     *
     * @param string $word
     * @return Line
     */
    public function add(string $word): self
    {
        $this->segments[] = $word;

        return $this;
    }

    /**
     * Returns Iterator
     *
     * @return Traversable<string>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->segments);
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
     * @param PointInterface $point
     * @return Line
     */
    public function setPosition(PointInterface $point): self
    {
        $this->position = $point;

        return $this;
    }

    /**
     * Count segments (individual words including punctuation marks) of line
    *
     * @return int
     */
    public function count(): int
    {
        return count($this->segments);
    }

    /**
     * Count characters of line
     *
     * @return int
     */
    public function length(): int
    {
        return mb_strlen((string) $this);
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
