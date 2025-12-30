<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResizeCanvasModifier as GenericResizeCanvasModifier;

class ResizeCanvasModifier extends GenericResizeCanvasModifier implements SpecializedInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $cropSize = $this->cropSize($image);

        $image->modify(new CropModifier(
            $cropSize->width(),
            $cropSize->height(),
            $cropSize->pivot()->x(),
            $cropSize->pivot()->y(),
            $this->backgroundColor(),
        ));

        return $image;
    }
}
