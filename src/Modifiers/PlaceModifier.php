<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;

class PlaceModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
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
     * @throws RuntimeException
     */
    public function position(ImageInterface $image, ImageInterface $watermark): PointInterface
    {
        $image_size = $image->size()->movePivot(
            $this->alignment,
            $this->x,
            $this->y
        );

        $watermark_size = $watermark->size()->movePivot(
            $this->alignment
        );

        return $image_size->relativePositionTo($watermark_size);
    }
}
