<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Size;

class CoverModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     */
    public function __construct(
        public int $width,
        public int $height,
        public string|Alignment $alignment = Alignment::CENTER
    ) {
        //
    }

    /**
     * Calculate crop size of the cover resizing process
     *
     * @throws InvalidArgumentException
     */
    protected function cropSize(ImageInterface $image): SizeInterface
    {
        $imagesize = $image->size();
        $crop = new Size($this->width, $this->height);

        return $crop->contain(
            $imagesize->width(),
            $imagesize->height()
        )->alignPivotTo($imagesize, $this->alignment);
    }

    /**
     * Calculate size for the resizing step of the cover modifier
     */
    protected function resizeSize(SizeInterface $size): SizeInterface
    {
        return $size->resize($this->width, $this->height);
    }
}
