<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Interfaces\ColorInterface;

class Bin
{
    /**
     * Create new instance.
     */
    public function __construct(public ColorInterface $color, public int $count = 0)
    {
        //
    }

    /**
     * Increase count.
     */
    public function increaseCount(int $count = 1): void
    {
        $this->count = $this->count + $count;
    }
}
