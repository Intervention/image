<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class RotateModifier implements ModifierInterface
{
    /**
     * Rotation angle
     *
     * @var float
     */
    protected $angle;

    /**
     * Background color
     *
     * @var mixed
     */
    protected $backgroundcolor;

    /**
     * Create new modifier
     *
     * @param float $angle
     */
    public function __construct(float $angle, $backgroundcolor = null)
    {
        $this->angle = $angle;
        $this->backgroundcolor = $backgroundcolor;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            imagerotate($frame->getCore(), $this->rotationAngle(), 0);
        }

        return $image;
    }

    protected function rotationAngle(): float
    {
        // restrict rotations beyond 360 degrees, since the end result is the same
        return fmod($this->angle, 360);
    }
}
