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
     * Calculate the crop size of the contain resizing process
     *
     * @throws InvalidArgumentException
     */
    protected function cropSize(ImageInterface $image): SizeInterface
    {
        return $image->size()
            ->contain(
                $this->width,
                $this->height
            )
            ->alignPivotTo(
                $this->resizeSize($image),
                $this->alignment
            );
    }

    /**
     * Calculate the resize target size of the contain resizing process
     *
     * @throws InvalidArgumentException
     */
    protected function resizeSize(ImageInterface $image): SizeInterface
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
