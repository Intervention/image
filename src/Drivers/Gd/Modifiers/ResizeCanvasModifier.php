<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\ResizeCanvasModifier as GenericResizeCanvasModifier;

class ResizeCanvasModifier extends GenericResizeCanvasModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $cropSize = $this->cropSize($image);

        $image->modify(new CropModifier(
            $cropSize->width(),
            $cropSize->height(),
            $cropSize->pivot()->x(),
            $cropSize->pivot()->y(),
            $this->background,
        ));

        return $image;
    }
}
