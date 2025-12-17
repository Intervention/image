<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\SizeInterface;

class CoverDownModifier extends CoverModifier
{
    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->resizeDown($this->width, $this->height);
    }
}
