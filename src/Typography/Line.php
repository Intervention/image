<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use ArrayIterator;
use Countable;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\PointInterface;
use IteratorAggregate;
use Stringable;
use Traversable;

/**
 * @implements IteratorAggregate<string>
 */
class Line implements IteratorAggregate, Countable, Stringable
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
     * @return void
     */
    public function __construct(
        ?string $text = null,
        protected PointInterface $position = new Point()
    ) {
        if (is_string($text)) {
            $this->segments = $this->wordsSeperatedBySpaces($text) ? explode(" ", $text) : mb_str_split($text);
        }
    }

    /**
     * Add word to current line
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
     */
    public function position(): PointInterface
    {
        return $this->position;
    }

    /**
     * Set position of current line
     */
    public function setPosition(PointInterface $point): self
    {
        $this->position = $point;

        return $this;
    }

    /**
     * Count segments (individual words including punctuation marks) of line
     */
    public function count(): int
    {
        return count($this->segments);
    }

    /**
     * Count characters of line
     */
    public function length(): int
    {
        return mb_strlen((string) $this);
    }

    /**
     * Dermine if words are sperarated by spaces in the written language of the given text
     */
    private function wordsSeperatedBySpaces(string $text): bool
    {
        return 1 !== preg_match(
            '/[' .
            '\x{4E00}-\x{9FFF}' . // CJK Unified Ideographs (chinese)
            '\x{3400}-\x{4DBF}' . // CJK Unified Ideographs Extension A (chinese)
            '\x{3040}-\x{309F}' . // hiragana (japanese)
            '\x{30A0}-\x{30FF}' . // katakana (japanese)
            '\x{0E00}-\x{0E7F}' . // thai
            ']/u',
            $text
        );
    }

    /**
     * Cast line to string
     */
    public function __toString(): string
    {
        $string = implode("", $this->segments);

        if ($this->wordsSeperatedBySpaces($string)) {
            return implode(" ", $this->segments);
        }

        return $string;
    }
}
