<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\ConfigInterface;

class Config implements ConfigInterface
{
    public function __construct(
        protected bool $autoOrientation = true,
        protected bool $decodeAnimation = true,
        protected mixed $blendingColor = 'ffffff00',
    ) {
    }

    public function decodeAnimation(): bool
    {
        return $this->decodeAnimation;
    }

    public function setDecodeAnimation(bool $status): self
    {
        $this->decodeAnimation = $status;

        return $this;
    }

    public function autoOrientation(): bool
    {
        return $this->autoOrientation;
    }

    public function setAutoOrientation(bool $status): self
    {
        $this->autoOrientation = $status;

        return $this;
    }

    public function blendingColor(): mixed
    {
        return $this->blendingColor;
    }

    public function setBlendingColor(mixed $color): self
    {
        $this->blendingColor = $color;

        return $this;
    }
}
