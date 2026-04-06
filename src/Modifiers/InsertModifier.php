<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\PointInterface;

class InsertModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        public mixed $image,
        public int $x = 0,
        public int $y = 0,
        public string|Alignment $alignment = Alignment::TOP_LEFT,
        public float $transparency = 1
    ) {
        if ($this->transparency < 0 || $this->transparency > 1) {
            throw new InvalidArgumentException('Transparency must be in range 0 to 1');
        }
    }

    /**
     * Calculate position of the watermark to be inserted on the image.
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

        return $imageSize->offsetTo($watermarkSize);
    }
}
