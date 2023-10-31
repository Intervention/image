<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Traits\CanCheckType;

class RemoveAnimationModifier implements ModifierInterface
{
    use CanCheckType;

    public function __construct(protected int $position = 0)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        if (!$image->isAnimated()) {
            throw new RuntimeException('Image is not animated.');
        }

        $image = $this->failIfNotClass($image, Image::class);

        // create new imagick with just one image
        $imagick = new Imagick();
        foreach ($image->getImagick() as $key => $core) {
            if ($key == $this->position) {
                $imagick->addImage($core->getImage());
            }
        }

        return $image->setImagick($imagick);
    }
}
