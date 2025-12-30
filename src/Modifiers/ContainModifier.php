<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;

class ContainModifier extends SpecializableModifier
{
    public function __construct(
        public int $width,
        public int $height,
        public mixed $background = null,
        public string|Alignment $alignment = Alignment::CENTER
    ) {
        //
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCropSize(ImageInterface $image): SizeInterface // TODO: make protected, rename
    {
        return $image->size()
            ->contain(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->getResizeSize($image),
                $this->alignment
            );
    }

    /**
     * Return target size for resizing
     *
     * @throws InvalidArgumentException
     */
    public function getResizeSize(ImageInterface $image): SizeInterface // TODO: make protected, rename
    {
        return new Size($this->width, $this->height);
    }

    /**
     * Return color to fill the newly created areas after rotation
     *
     * @throws StateException
     */
    protected function backgroundColor(): ColorInterface
    {
        return $this->driver()->handleColorInput(
            $this->background ?? $this->driver()->config()->backgroundColor,
        );
    }
}
