<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\SizeInterface;

enum Orientation: string
{
    case PORTRAIT = 'portrait';
    case LANDSCAPE = 'landscape';
    case SQUARE = 'square';

    /**
     * Create orientation of given size.
     */
    public static function fromSize(SizeInterface $size): self
    {
        return match (true) {
            $size->isPortrait() => self::PORTRAIT,
            $size->isLandscape() => self::LANDSCAPE,
            default => self::SQUARE,
        };
    }
}
