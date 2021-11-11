<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanHandleInput;

class RotateModifier implements ModifierInterface
{
    use CanHandleInput;

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
    protected $backgroundColor;

    /**
     * Create new modifier
     *
     * @param float $angle
     */
    public function __construct(float $angle, $backgroundColor = null)
    {
        $this->angle = $angle;
        $this->backgroundColor = $backgroundColor;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $frame->setCore(
                imagerotate($frame->getCore(), $this->rotationAngle(), $this->backgroundColor())
            );
        }

        return $image;
    }

    protected function rotationAngle(): float
    {
        // restrict rotations beyond 360 degrees, since the end result is the same
        return fmod($this->angle, 360);
    }

    protected function backgroundColor(): int
    {
        $color = $this->handleInput($this->backgroundColor);

        echo "<pre>";
        var_dump($color);
        echo "</pre>";
        exit;
    }
}
