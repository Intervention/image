<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ResizeCanvasModifier extends SpecializableModifier
{
    public function __construct(
        public ?int $width = null,
        public ?int $height = null,
        public mixed $background = 'ffffff',
        public string $position = 'center'
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function cropSize(ImageInterface $image): SizeInterface
    {
        $width = is_null($this->width) ? $image->width() : $this->width;
        $height = is_null($this->height) ? $image->height() : $this->height;

        return (new Rectangle($width, $height))
            ->alignPivotTo(
                $image->size(),
                $this->position
            );
    }
}
