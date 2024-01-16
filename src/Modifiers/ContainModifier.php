<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ContainModifier extends SpecializableModifier
{
    public function __construct(
        public int $width,
        public int $height,
        public mixed $background = 'ffffff',
        public string $position = 'center'
    ) {
    }

    public function getCropSize(ImageInterface $image): SizeInterface
    {
        return $image->size()
            ->contain(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->getResizeSize($image),
                $this->position
            );
    }

    public function getResizeSize(ImageInterface $image): SizeInterface
    {
        return new Rectangle($this->width, $this->height);
    }
}
