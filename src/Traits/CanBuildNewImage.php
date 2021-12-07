<?php

namespace Intervention\Image\Traits;

use Intervention\Image\Interfaces\FactoryInterface;

trait CanBuildNewImage
{
    use CanResolveDriverClass;

    public function imageFactory(): FactoryInterface
    {
        return $this->resolveDriverClass('ImageFactory');
    }
}
