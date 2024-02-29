<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class CropModifier extends SpecializableModifier
{
    public function __construct(
        public int $width,
        public int $height,
        public int $offset_x = 0,
        public int $offset_y = 0,
        public mixed $background = 'f00',
        public string $position = 'top-left'
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function crop(ImageInterface $image): SizeInterface
    {
        $crop = new Rectangle($this->width, $this->height);
        $crop->align($this->position);

        return $crop->alignPivotTo(
            $image->size(),
            $this->position
        );
    }
}
