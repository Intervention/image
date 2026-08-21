<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Interfaces\ThemeDefinitionInterface;

enum Theme
{
    case VIBRANT_MUTED;

    public function definition(): ThemeDefinitionInterface
    {
        return match ($this) {
            self::VIBRANT_MUTED => new Themes\VibrantMuted\Definition(),
        };
    }
}
