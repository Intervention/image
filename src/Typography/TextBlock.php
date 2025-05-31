<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Collection;

class TextBlock extends Collection
{
    /**
     * Create new text block object
     *
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
     */
    public function setLines(array $lines): self
    {
        $this->items = $lines;

        return $this;
    }

    /**
     * Get line by given key
     */
    public function line(mixed $key): ?Line
    {
        if (!array_key_exists($key, $this->lines())) {
            return null;
        }

        return $this->lines()[$key];
    }

    /**
     * Return line with most characters of text block
     */
    public function longestLine(): Line
    {
        $lines = $this->lines();
        usort($lines, function (Line $a, Line $b): int {
            if ($a->length() === $b->length()) {
                return 0;
            }
            return $a->length() > $b->length() ? -1 : 1;
        });

        return $lines[0];
    }
}
