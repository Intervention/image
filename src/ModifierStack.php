<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ModifierStack implements ModifierInterface
{
    /**
     * Create new modifier stack object with an array of modifier objects
     *
     * @param array $modifiers
     * @return void
     */
    public function __construct(protected array $modifiers)
    {
    }

    /**
     * Apply all modifiers in stack to the given image
     *
     * @param ImageInterface $image
     * @return ImageInterface
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->apply($image);
        }

        return $image;
    }

    /**
     * Append new modifier to the stack
     *
     * @param ModifierInterface $modifier
     * @return ModifierStack
     */
    public function push(ModifierInterface $modifier): self
    {
        $this->modifiers[] = $modifier;

        return $this;
    }
}
