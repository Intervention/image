<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\FrameInterface;

abstract class AbstractFrame implements FrameInterface
{
    /**
     * Show debug info for the current image
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'delay' => $this->delay(),
            'left' => $this->offsetLeft(),
            'top' => $this->offsetTop(),
            'dispose' => $this->dispose(),
        ];
    }
}
