<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AnimationFactory implements AnimationFactoryInterface
{
    public function __construct(
        protected DriverInterface $driver,
        public Imagick $imagick = new Imagick()
    ) {
        $this->imagick->setFormat('gif');
    }

    public function add(mixed $source, float $delay = 1): self
    {
        $native = $this->driver->handleImageInput($source)->core()->native();
        $native->setImageDelay(intval(round($delay * 100)));

        $this->imagick->addImage($native);

        return $this;
    }

    public function __invoke(): ImageInterface
    {
        return new Image(
            $this->driver,
            new Core($this->imagick)
        );
    }
}
