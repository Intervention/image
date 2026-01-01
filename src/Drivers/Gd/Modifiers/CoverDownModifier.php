<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\SizeInterface;

class CoverDownModifier extends CoverModifier
{
    /**
     * Calculate resizing size of the cover down process
     */
    protected function resizeSize(SizeInterface $size): SizeInterface
    {
        return $size->resizeDown($this->width, $this->height);
    }
}
