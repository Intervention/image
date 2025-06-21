<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;

class ContainModifier extends SpecializableModifier
{
    public function __construct(
        public int $width,
        public int $height,
        public mixed $background = null,
        public string $position = 'center'
    ) {
        //
    }

    /**
     * @throws RuntimeException
     */
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

    /**
     * Return target size for resizing
     */
    public function getResizeSize(ImageInterface $image): SizeInterface
    {
        return new Rectangle($this->width, $this->height);
    }

    /**
     * Return color to fill the newly created areas after rotation
     *
     * @throws RuntimeException
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleInput($this->background ?? $this->driver()->config()->backgroundColor);
    }
}
