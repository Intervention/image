<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ReduceColorsModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     */
    public function __construct(
        public int $limit,
        public null|string|ColorInterface $background = null
    ) {
        if ($this->limit < 1) {
            throw new InvalidArgumentException('Invalid color limit. Only use int<1, max>');
        }
    }

    /**
     * Return color in colorspace of image to fill transparent areas.
     *
     * @throws StateException
     */
    protected function backgroundColor(ImageInterface $image): ColorInterface
    {
        return $this->driver()->decodeColor(
            $this->background ?? $this->driver()->config()->backgroundColor,
        )->toColorspace($image->colorspace());
    }
}
