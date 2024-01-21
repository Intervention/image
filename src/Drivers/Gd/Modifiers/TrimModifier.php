<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\DriverSpecialized;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

/**
 * @property int $tolerance
 */
class TrimModifier extends DriverSpecialized implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        if ($image->isAnimated()) {
            throw new NotSupportedException('Animated Images do not support trim operation');
        }

        $gd      = $image->core()->native();
        $width   = imagesx($image->core()->native());
        $height  = imagesy($image->core()->native());
        $red     = 0;
        $green   = 0;
        $blue    = 0;
        $corners = [
            [0,0],
            [$width - 1,0],
            [0,$height - 1],
            [$width - 1,$height - 1],
        ];

        foreach ($corners as $corner) {
            $thisColor = imagecolorat($gd, $corner[0], $corner[1]);

            $rgb    = imagecolorsforindex($gd, $thisColor);
            $red   += round(round(($rgb['red'] / 0x33)) * 0x33);
            $green += round(round(($rgb['green'] / 0x33)) * 0x33);
            $blue  += round(round(($rgb['blue'] / 0x33)) * 0x33);
        }

        $red   /= 4;
        $green /= 4;
        $blue  /= 4;

        $trimColor = imagecolorallocate($gd, (int)$red, (int)$green, (int)$blue);
        $trimmed   = imagecropauto($gd, IMG_CROP_THRESHOLD, $this->tolerance, $trimColor);

        $image->core()->setNative($trimmed);

        imagedestroy($gd);
        imagedestroy($trimmed);

        return $image;
    }
}
