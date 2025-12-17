<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeCanvasModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(
        public ?int $width = null,
        public ?int $height = null,
        public mixed $background = null,
        public string|Alignment $alignment = Alignment::CENTER
    ) {
        //
    }

    /**
     * Build the crop size to be used for the ResizeCanvas process
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

        return $size->alignPivotTo($image->size(), $this->alignment);
    }

    /**
     * Return color to fill the newly created areas after rotation
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput(
            $this->background ?? $this->driver()->config()->backgroundColor,
        );
    }
}
