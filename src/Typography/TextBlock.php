<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Collection;

class TextBlock extends Collection
{
    /**
     * Create new text block object
     *
     * @param string $text
     * @return void
     */
    public function __construct(string $text)
    {
        foreach (explode("\n", $text) as $line) {
            $this->push(new Line($line));
        }
    }

    /**
     * Return array of lines in text block
     *
     * @return array<Line>
     */
    public function lines(): array
    {
        return $this->items;
    }

    /**
     * Set lines of the text block
     *
     * @param array<Line> $lines
     * @return self
     */
    public function setLines(array $lines): self
    {
        $this->items = $lines;

        return $this;
    }

    /**
     * Get line by given key
     *
     * @param mixed $key
     * @return null|Line
     */
    public function line($key): ?Line
    {
        if (!array_key_exists($key, $this->lines())) {
            return null;
        }

        return $this->lines()[$key];
    }

    /**
     * Return line with most characters of text block
     *
     * @return Line
     */
    public function longestLine(): Line
    {
        $lines = $this->lines();
        usort($lines, function (Line $a, Line $b) {
            if ($a->length() === $b->length()) {
                return 0;
            }
            return $a->length() > $b->length() ? -1 : 1;
        });

        return $lines[0];
    }
}
