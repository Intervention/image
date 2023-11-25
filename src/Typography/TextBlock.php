<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Collection;

class TextBlock extends Collection
{
    public function __construct(string $text)
    {
        foreach (explode("\n", $text) as $line) {
            $this->push(new Line($line));
        }
    }

    /**
     * Return array of lines in text block
     *
     * @return array
     */
    public function lines(): array
    {
        return $this->items;
    }

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
        usort($lines, function ($a, $b) {
            if (mb_strlen($a) === mb_strlen($b)) {
                return 0;
            }
            return (mb_strlen($a) > mb_strlen($b)) ? -1 : 1;
        });

        return $lines[0];
    }
}
