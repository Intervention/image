<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;

class InsertModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     */
    public function __construct(
        public mixed $element,
        public string|Alignment $alignment = Alignment::TOP_LEFT,
        public int $x = 0,
        public int $y = 0,
        public int $opacity = 100
    ) {
        //
    }

    /**
     * Calculate position of the element to be inserted on the image.
     */
    public function position(ImageInterface $image, ImageInterface $watermark): PointInterface
    {
        $imageSize = $image->size()->movePivot(
            $this->alignment,
            $this->x,
            $this->y
        );

        $watermarkSize = $watermark->size()->movePivot(
            $this->alignment
        );

        return $imageSize->relativePositionTo($watermarkSize);
    }
}
