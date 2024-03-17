<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\SpecializableInterface;
use Intervention\Image\Traits\CanBeDriverSpecialized;

abstract class SpecializableEncoder extends AbstractEncoder implements SpecializableInterface
{
    use CanBeDriverSpecialized;
}
