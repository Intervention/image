<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\ConfigInterface;

class Config implements ConfigInterface
{
    public function __construct(
        protected bool $autoOrientate = true,
        protected bool $decodeAnimation = true,
        protected mixed $blendingColor = 'ffffffff',
    ) {
    }

    public function decodeAnimation(): bool
    {
        return $this->decodeAnimation;
    }

    public function autoOrientate(): bool
    {
        return $this->autoOrientate;
    }

    public function blendingColor(): mixed
    {
        return $this->blendingColor;
    }
}
