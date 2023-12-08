<?php

namespace Intervention\Image;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ModifierStack implements ModifierInterface
{
    public function __construct(protected array $modifiers)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->apply($image);
        }

        return $image;
    }

    public function push(ModifierInterface $modifier): self
    {
        $this->modifiers[] = $modifier;

        return $this;
    }
}
