<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class SharpenModifier implements ModifierInterface
{
    public function __construct(protected int $amount)
    {
        //
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $matrix = $this->matrix();
        foreach ($image as $frame) {
            imageconvolution($frame->getCore(), $matrix, 1, 0);
        }

        return $image;
    }

    protected function matrix(): array
    {
        $min = $this->amount >= 10 ? $this->amount * -0.01 : 0;
        $max = $this->amount * -0.025;
        $abs = ((4 * $min + 4 * $max) * -1) + 1;

        return [
            [$min, $max, $min],
            [$max, $abs, $max],
            [$min, $max, $min]
        ];
    }
}
