<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ModifierStack implements ModifierInterface
{
    /**
     * Create new modifier stack object with an array of modifier objects.
     *
     * @param array<ModifierInterface> $modifiers
     */
    public function __construct(protected array $modifiers)
    {
        //
    }

    /**
     * Create new modifier stack object statically.
     *
     * @param array<ModifierInterface> $modifiers
     */
    public static function create(array $modifiers): self
    {
        return new self($modifiers);
    }

    /**
     * Apply all modifiers in stack to the given image.
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->apply($image);
        }

        return $image;
    }

    /**
     * Append new modifier to the stack.
     */
    public function push(ModifierInterface $modifier): self
    {
        $this->modifiers[] = $modifier;

        return $this;
    }
}
