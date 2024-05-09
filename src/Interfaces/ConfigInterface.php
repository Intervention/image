<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ConfigInterface
{
    public function decodeAnimation(): bool;

    public function autoOrientation(): bool;

    public function blendingColor(): mixed;

    public function setBlendingColor(mixed $color): mixed;
}
