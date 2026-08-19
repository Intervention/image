<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Themes\VibrantMuted;

use Intervention\Image\Colors\Themes\AbstractTheme;
use Intervention\Image\Interfaces\ColorInterface;

class Theme extends AbstractTheme
{
    /**
     * Create new instance.
     */
    public function __construct(
        public ?ColorInterface $vibrant = null,
        public ?ColorInterface $muted = null,
        public ?ColorInterface $darkVibrant = null,
        public ?ColorInterface $darkMuted = null,
        public ?ColorInterface $lightVibrant = null,
        public ?ColorInterface $lightMuted = null,
    ) {
        //
    }
}
