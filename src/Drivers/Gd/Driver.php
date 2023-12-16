<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;

class Driver extends AbstractDriver
{
    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::id()
     */
    public function id(): string
    {
        return 'GD';
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createImage()
     */
    public function createImage(int $width, int $height): ImageInterface
    {
        // build new transparent GDImage
        $data = imagecreatetruecolor($width, $height);
        imagesavealpha($data, true);
        $background = imagecolorallocatealpha($data, 255, 0, 255, 127);
        imagealphablending($data, false);
        imagefill($data, 0, 0, $background);
        imagecolortransparent($data, $background);

        return new Image(
            $this,
            new Core([
                new Frame($data)
            ])
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::createAnimation()
     */
    public function createAnimation(callable $init): ImageInterface
    {
        $animation = new class ($this)
        {
            public function __construct(
                protected DriverInterface $driver,
                public Core $core = new Core()
            ) {
            }

            public function add($source, float $delay = 1): self
            {
                $this->core->add(
                    $this->driver->handleInput($source)->core()->first()->setDelay($delay)
                );

                return $this;
            }

            public function __invoke(): ImageInterface
            {
                return new Image(
                    $this->driver,
                    $this->core
                );
            }
        };

        $init($animation);

        return call_user_func($animation);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::handleInput()
     */
    public function handleInput(mixed $input): ImageInterface|ColorInterface
    {
        return (new InputHandler())->handle($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::colorProcessor()
     */
    public function colorProcessor(ColorspaceInterface $colorspace): ColorProcessorInterface
    {
        return new ColorProcessor($colorspace);
    }
}
