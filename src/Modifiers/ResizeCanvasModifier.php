<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeCanvasModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @param null|int $width
     * @param null|int $height
     * @param mixed $background
     * @param string $position
     * @return void
     */
    public function __construct(
        public ?int $width = null,
        public ?int $height = null,
        public mixed $background = 'ffffff',
        public string $position = 'center'
    ) {
    }

    /**
     * Build the crop size to be used for the ResizeCanvas process
     *
     * @param ImageInterface $image
     * @param bool $relative
     * @throws RuntimeException
     * @return SizeInterface
     */
    protected function cropSize(ImageInterface $image, bool $relative = false): SizeInterface
    {

        $size = match ($relative) {
            true => new Rectangle(
                is_null($this->width) ? $image->width() : $image->width() + $this->width,
                is_null($this->height) ? $image->height() : $image->height() + $this->height,
            ),
            default => new Rectangle(
                is_null($this->width) ? $image->width() : $this->width,
                is_null($this->height) ? $image->height() : $this->height,
            ),
        };

        return $size->alignPivotTo($image->size(), $this->position);
    }
}
