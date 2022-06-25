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
}
