<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Interfaces\SizeInterface;

class CoverDownModifier extends CoverModifier
{
    /**
     * @throws GeometryException
     */
    public function getResizeSize(SizeInterface $size): SizeInterface
    {
        return $size->resizeDown($this->width, $this->height);
    }
}
