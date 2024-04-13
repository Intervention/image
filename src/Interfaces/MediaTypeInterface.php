<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Format;

interface MediaTypeInterface
{
    public function format(): Format;
}
