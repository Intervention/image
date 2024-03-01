<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Interfaces\SizeInterface;

/**
 * @property int $width
 * @property int $height
 */
class CoverDownModifier extends CoverModifier
{
    /**
     * @throws GeometryException
     */
    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->scaleDown($this->width, $this->height);
    }
}
