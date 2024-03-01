<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;

class PlaceModifier extends SpecializableModifier
{
    public function __construct(
        public mixed $element,
        public string $position,
        public int $offset_x,
        public int $offset_y,
        public int $opacity = 100
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function getPosition(ImageInterface $image, ImageInterface $watermark): PointInterface
    {
        $image_size = $image->size()->movePivot(
            $this->position,
            $this->offset_x,
            $this->offset_y
        );

        $watermark_size = $watermark->size()->movePivot(
            $this->position
        );

        return $image_size->relativePositionTo($watermark_size);
    }
}
