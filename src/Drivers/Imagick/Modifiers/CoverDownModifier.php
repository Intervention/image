<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\SizeInterface;

/**
 * @property int $width
 * @property int $height
 */
class CoverDownModifier extends CoverModifier
{
    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->scaleDown($this->width, $this->height);
    }
}
