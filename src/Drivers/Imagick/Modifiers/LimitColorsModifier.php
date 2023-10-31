<?php

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanCheckType;
use Intervention\Image\Drivers\Imagick\Image;

class LimitColorsModifier implements ModifierInterface
{
    use CanCheckType;

    public function __construct(protected int $limit = 0, protected $threshold = 256)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        // no color limit: no reduction
        if ($this->limit === 0) {
            return $image;
        }

        // limit is over threshold: no reduction
        if ($this->limit > $this->threshold) {
            return $image;
        }

        $image = $this->failIfNotClass($image, Image::class);
        foreach ($image->getImagick() as $core) {
            $core->quantizeImage(
                $this->limit,
                $core->getImageColorspace(),
                0,
                false,
                false
            );
        }

        return $image;
    }
}
