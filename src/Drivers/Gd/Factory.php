<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FactoryInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanHandleInput;

class DELETE___Factory implements FactoryInterface
{
    use CanHandleInput;
    use CanHandleColors;

    /**
     * {@inheritdoc}
     *
     * @see FactoryInterface::newImage()
     */
    public function newImage(int $width, int $height): ImageInterface
    {
        return new Image(
            new Collection([
                new Frame($this->newCore($width, $height))
            ])
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see FactoryInterface::newAnimation()
     */
    public function newAnimation(callable $callback): ImageInterface
    {
        $frames = new Collection();

        $animation = new class ($frames) extends Factory
        {
            public function __construct(public Collection $frames)
            {
                //
            }

            public function add($source, float $delay = 1): self
            {
                $this->frames->push(
                    $this->handleInput($source)
                        ->frame()
                        ->setDelay($delay)
                );

                return $this;
            }
        };

        $callback($animation);

        return new Image($frames);
    }

    /**
     * {@inheritdoc}
     *
     * @see FactoryInterface::newCore()
     */
    public function newCore(int $width, int $height, ?ColorInterface $background = null)
    {
        $core = imagecreatetruecolor($width, $height);

        imagesavealpha($core, true);

        $color = match (is_null($background)) {
            true => imagecolorallocatealpha($core, 255, 0, 255, 127),
            default => $this->allocateColor($core, $background),
        };

        imagealphablending($core, false);
        imagefill($core, 0, 0, $color);
        if ($background && $background->channel(Alpha::class)->value() == 0) {
            imagecolortransparent($core, $color);
        }

        return $core;
    }
}
