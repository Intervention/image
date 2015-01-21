<?php

namespace Intervention\Image\Gd;

use \Intervention\Image\ContainerInterface;
use \Intervention\Image\Size;
use \Intervention\Image\Frame;

class Driver extends \Intervention\Image\AbstractDriver
{
    /**
     * Creates new instance of driver
     *
     * @param Decoder $decoder
     * @param Encoder $encoder
     */
    public function __construct(Decoder $decoder = null, Encoder $encoder = null)
    {
        if ( ! $this->moduleAvailable()) {
            throw new \Intervention\Image\Exception\NotSupportedException(
                "GD Library extension not available with this PHP installation."
            );
        }

        $this->decoder = $decoder ? $decoder : new Decoder;
        $this->encoder = $encoder ? $encoder : new Encoder;
    }

    /**
     * Creates new image instance
     *
     * @param  integer $width
     * @param  integer $height
     * @param  string  $background
     * @return \Intervention\Image\Image
     */
    public function newImage($width, $height, $background = null)
    {
        // create empty resource
        $core = imagecreatetruecolor($width, $height);
        $image = new \Intervention\Image\Image(new self, $this->newContainer($core));

        // set background color
        $background = new Color($background);
        imagefill($image->getCore(), 0, 0, $background->getInt());

        return $image;
    }

    /**
     * Creates a new animation image instance
     *
     * @param  integer  $width
     * @param  integer  $height
     * @param  \Closure $callback
     * @param  integer  $loops
     *
     * @return \Intervention\Image\Image
     */
    public function newAnimation($width, $height, $callback = null, $loops = null)
    {
        // create container
        $container = new Container;
        $container->setLoops($loops);

        // create empty canvas
        $canvas = $this->newImage($width, $height)->getCore();

        // build frames from callback
        if (is_callable($callback)) {

            $callback($container);
            
        } else {
            $container->setCore($canvas);
        }

        // setup image instance
        $image = new \Intervention\Image\Image(new self, $container);

        return $image;
    }

    /**
     * Reads given string into color object
     *
     * @param  string $value
     * @return AbstractColor
     */
    public function parseColor($value)
    {
        return new Color($value);
    }

    /**
     * Checks if image module installation is available
     *
     * @return boolean
     */
    protected function moduleAvailable()
    {
        return (extension_loaded('gd') && function_exists('gd_info'));
    }

    /**
     * Returns clone of given core
     *
     * @return mixed
     */
    public function cloneCore($core)
    {
        $width = imagesx($core);
        $height = imagesy($core);
        $clone = imagecreatetruecolor($width, $height);
        imagealphablending($clone, false);
        imagesavealpha($clone, true);
        
        imagecopy($clone, $core, 0, 0, 0, 0, $width, $height);

        return $clone;
    }

    /**
     * Returns clone of current container
     *
     * @param  ContainerInterface $container
     * @return \Intervention\Image\Gd\Container
     */
    public function cloneContainer(ContainerInterface $container)
    {
        $cloneContainer = clone $container;
        $cloneFrames = array();

        // clone each resource of container
        foreach ($container as $frame) {
            $cloneFrames[] = new Frame($this->cloneCore($frame->getCore()));
        }

        $cloneContainer->setFrames($cloneFrames);

        return $cloneContainer;
    }

    /**
     * Builds new container from GD resource
     *
     * @param  resource $resource
     * @return \Intervention\Image\Gd\Container
     */
    public function newContainer($resource)
    {
        $container = new Container;

        $container->setFrames(array(new Frame($resource)));

        return $container;
    }
}
