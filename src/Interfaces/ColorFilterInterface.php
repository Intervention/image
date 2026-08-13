<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Colors\Histogram;

interface ColorFilterInterface
{
    public function filterColors(Histogram $histogram): SwatchesInterface;
}
