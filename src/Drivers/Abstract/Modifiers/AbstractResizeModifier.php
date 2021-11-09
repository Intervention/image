<?php

namespace Intervention\Image\Drivers\Abstract\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResizeGeometrically;

abstract class AbstractResizeModifier implements ModifierInterface
{
    use CanResizeGeometrically;

    protected $target;

    public function __construct(SizeInterface $target)
    {
        $this->target = $target;
    }

    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        return $image->getSize();
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return $this->target;
    }
}
