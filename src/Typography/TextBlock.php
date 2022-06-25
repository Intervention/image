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

    public function lines(): array
    {
        return $this->items;
    }

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
