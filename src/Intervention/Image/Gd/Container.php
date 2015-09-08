<?php

namespace Intervention\Image\Gd;

use Intervention\Gif\Decoded;
use Intervention\Gif\Encoder as GifEncoder;
use Intervention\Image\Animation;
use Intervention\Image\ContainerInterface;
use Intervention\Image\Frame;

class Container extends Animation implements ContainerInterface
{
    /**
     * Return first image resource
     *
     * @param interger $index
     * @return resource
     */
    public function getCore($index = null)
    {
        $index = is_numeric($index) ? $index : 0;
        $frames = $this->getFrames();

        if (array_key_exists($index, $frames)) {
            return $frames[$index]->getCore();
        }

        throw new \Intervention\Image\Exception\NotFoundException(
            "Animation has no index with number {$index}."
        );
    }

    /**
     * Setup image stack with new resource
     *
     * @param resource $core
     * @return \Intervention\Image\Gd\Container
     */
    public function setCore($core)
    {
        $this->setFrames(array(
            new Frame($core)
        ));

        return $this;
    }

    /**
     * Return number of frames in container
     *
     * @return int
     */
    public function countFrames()
    {
        return count($this->getFrames());
    }

    /**
     * Add image source to Container
     *
     * @param mixed   $source
     * @param integer $delay
     * @return \Intervention\Image\Gd\Container
     */
    public function add($source, $delay = 0)
    {
        $driver = new Driver;

        $this->addFrame(new Frame(
            $driver->init($source)->getCore(),
            $delay
        ));

        return $this;
    }

    public static function initFromDecoded(Decoded $decoded)
    {
        $container = new self;
        $container->setLoops($decoded->getLoops());

        // create empty canvas
        $driver = new Driver;
        $canvas = $driver->newImage($decoded->getCanvasWidth(), $decoded->getCanvasHeight())->getCore();

        foreach ($decoded->getFrames() as $key => $frame) {

            // create resource from frame
            $encoder = new GifEncoder;
            $encoder->setFromDecoded($decoded, $key);
            $frame_resource = imagecreatefromstring($encoder->encode());

            // insert frame image data into canvas
            imagecopy(
                $canvas,
                $frame_resource,
                $frame->getOffset()->left,
                $frame->getOffset()->top,
                0,
                0,
                $frame->getSize()->width,
                $frame->getSize()->height
            );

            // destory frame resource
            imagedestroy($frame_resource);

            // add frame to container
            $container->addFrame(new \Intervention\Image\Frame(
                $canvas, 
                $frame->getDelay()
            ));

            // prepare next canvas
            $canvas = Helper::cloneResource($canvas);
        }

        return $container;
    
    }
}
