<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class CropModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @param int $width
     * @param int $height
     * @param int $offset_x
     * @param int $offset_y
     * @param mixed $background
     * @param string $position
     * @return void
     */
    public function __construct(
        public int $width,
        public int $height,
        public int $offset_x = 0,
        public int $offset_y = 0,
        public mixed $background = 'ffffff',
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
