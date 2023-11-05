<?php

namespace Intervention\Image\Drivers\Imagick;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Interfaces\FactoryInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanCheckType;
use Intervention\Image\Traits\CanHandleInput;

class Factory implements FactoryInterface
{
    use CanHandleInput;
    use CanCheckType;

    /**
     * {@inheritdoc}
     *
     * @see FactoryInterface::newImage()
     */
    public function newImage(int $width, int $height): ImageInterface
    {
        return new Image($this->newCore($width, $height));
    }

    /**
     * {@inheritdoc}
     *
     * @see FactoryInterface::newAnimation()
     */
    public function newAnimation(callable $callback): ImageInterface
    {
        $imagick = new Imagick();
        $imagick->setFormat('gif');

        $animation = new class ($imagick) extends Factory
        {
            public function __construct(public Imagick $imagick)
            {
                //
            }

            public function add($source, float $delay = 1): self
            {
                $imagick = $this->failIfNotClass(
                    $this->handleInput($source),
                    Image::class,
                )->getImagick();
                $imagick->setImageDelay($delay * 100);

                $this->imagick->addImage($imagick);

                return $this;
            }
        };

        $callback($animation);

        return new Image($animation->imagick);
    }

    /**
     * {@inheritdoc}
     *
     * @see FactoryInterface::newCore()
     */
    public function newCore(int $width, int $height)
    {
        $imagick = new Imagick();
        $imagick->newImage($width, $height, new ImagickPixel('rgba(0, 0, 0, 0)'), 'png');
        $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setColorspace(Imagick::COLORSPACE_RGB);
        $imagick->setImageResolution(96, 96);

        return $imagick;
    }
}
