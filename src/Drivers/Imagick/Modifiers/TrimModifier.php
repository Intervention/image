<?php
namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class TrimModifier extends DriverSpecialized implements ModifierInterface {

    public function apply(ImageInterface $image): ImageInterface {
        if ($image->isAnimated()) {
            throw new NotSupportedException('Animated Images do not support trim operation');
        }

        /** @var \Imagick $imagick */
        $imagick = $image->core()->native();

        $imagick->trimImage($this->determineFuzz($imagick, $this->tolerance));
        $imagick->setImagePage(0, 0, 0, 0);
        $image->resizeDown($imagick->getImageWidth(), $imagick->getImageHeight());

        return $image;
    }

    protected function determineFuzz(\Imagick &$im, int $tolerance):float {
        return ($tolerance / 100) * $im::getQuantum();
    }
}