<?php

namespace Intervention\Image;

/**
 * @method Image backup(string $name = null)                                                                                                  Backups current image state as fallback for reset method under an optional name. Overwrites older state on every call, unless a different name is passed.
 * @method Image blur(int $amount = null)                                                                                                     Apply a gaussian blur filter with a optional amount on the current image. Use values between 0 and 100.
 * @method Image brightness(int $level)                                                                                                       Changes the brightness of the current image by the given level. Use values between -100 for min. brightness. 0 for no change and +100 for max. brightness.
 * @method Image cache(\Closure $callback, int $lifetime = null, bool $returnObj)                                                             Method to create a new cached image instance from a Closure callback. Pass a lifetime in minutes for the callback and decide whether you want to get an Intervention Image instance as return value or just receive the image stream.
 * @method Image canvas(integer $width, integer $height, mixed $bgcolor = null)                                                               Factory method to create a new empty image instance with given width and height. You can define a background-color optionally. By default the canvas background is transparent.
 * @method Image circle(int $radius, int $x, int $y, \Closure $callback = null)                                                               Draw a circle at given x, y, coordinates with given radius. You can define the appearance of the circle by an optional closure callback.
 * @method Image colorize(int $red, int $green, int $blue)                                                                                    Change the RGB color values of the current image on the given channels red, green and blue. The input values are normalized so you have to include parameters from 100 for maximum color value. 0 for no change and -100 to take out all the certain color on the image.
 * @method Image contrast(int $level)                                                                                                         Changes the contrast of the current image by the given level. Use values between -100 for min. contrast 0 for no change and +100 for max. contrast.
 * @method Image crop(int $width, int $height, int $x = null, int $y = null)                                                                  Cut out a rectangular part of the current image with given width and height. Define optional x,y coordinates to move the top-left corner of the cutout to a certain position.
 * @method Image destroy()                                                                                                                    Frees memory associated with the current image instance before the PHP script ends. Normally resources are destroyed automatically after the script is finished.
 * @method Image ellipse(int $width, int $height, int $x, int $y, \Closure $callback = null)                                                  Draw a colored ellipse at given x, y, coordinates. You can define width and height and set the appearance of the circle by an optional closure callback.
 * @method Image exif(string $key = null)                                                                                                     Read Exif meta data from current image.
 * @method Image fill(mixed $filling, int $x = null, int $y = null)                                                                           Fill current image with given color or another image used as tile for filling. Pass optional x, y coordinates to start at a certain point.
 * @method Image flip(mixed $mode = null)                                                                                                     Mirror the current image horizontally or vertically by specifying the mode.
 * @method Image fit(int $width, int $height = null, \Closure $callback, string $position = null)                                             Combine cropping and resizing to format image in a smart way. The method will find the best fitting aspect ratio of your given width and height on the current image automatically, cut it out and resize it to the given dimension. You may pass an optional Closure callback as third parameter, to prevent possible upsizing and a custom position of the cutout as fourth parameter.
 * @method Image gamma(float $correction)                                                                                                     Performs a gamma correction operation on the current image.
 * @method Image greyscale()                                                                                                                  Turns image into a greyscale version.
 * @method Image heighten(int $height, \Closure $callback = null)                                                                             Resizes the current image to new height, constraining aspect ratio. Pass an optional Closure callback as third parameter, to apply additional constraints like preventing possible upsizing.
 * @method Image insert(mixed $source, string $position = null, int $x, int $y)                                                               Paste a given image source over the current image with an optional position and a offset coordinate. This method can be used to apply another image as watermark because the transparency values are maintained.
 * @method Image interlace(bool $interlace)                                                                                                   Determine whether an image should be encoded in interlaced or standard mode by toggling interlace mode with a boolean parameter. If an JPEG image is set interlaced the image will be processed as a progressive JPEG.
 * @method Image invert()                                                                                                                     Reverses all colors of the current image.
 * @method Image limitColors(int $count, mixed $matte = null)                                                                                 Method converts the existing colors of the current image into a color table with a given maximum count of colors. The function preserves as much alpha channel information as possible and blends transarent pixels against a optional matte color.
 * @method Image line(int $x1, int $y1, int $x2, int $y2, \Closure $callback = null)                                                          Draw a line from x,y point 1 to x,y point 2 on current image. Define color and/or width of line in an optional Closure callback.
 * @method Image make(mixed $source)                                                                                                          Universal factory method to create a new image instance from source, which can be a filepath, a GD image resource, an Imagick object or a binary image data.
 * @method Image mask(mixed $source, bool $mask_with_alpha)                                                                                   Apply a given image source as alpha mask to the current image to change current opacity. Mask will be resized to the current image size. By default a greyscale version of the mask is converted to alpha values, but you can set mask_with_alpha to apply the actual alpha channel. Any transparency values of the current image will be maintained.
 * @method Image opacity(integer $transparency)                                                                                               Set the opacity in percent of the current image ranging from 100% for opaque and 0% for full transparency.
 * @method Image orientate()                                                                                                                  This method reads the EXIF image profile setting 'Orientation' and performs a rotation on the image to display the image correctly.
 * @method Image pickColor(int $x, int $y, string $format = null)                                                                             Pick a color at point x, y out of current image and return in optional given format.
 * @method Image pixel(mixed $color, int $x, int $y)                                                                                          Draw a single pixel in given color on x, y position.
 * @method Image pixelate(int $size)                                                                                                          Applies a pixelation effect to the current image with a given size of pixels.
 * @method Image polygon(array $points, \Closure $callback = null)                                                                            Draw a colored polygon with given points. You can define the appearance of the polygon by an optional closure callback.
 * @method Image rectangle(int $x1, int $y1, int $x2, int $y2, \Closure $callback = null)                                                     Draw a colored rectangle on current image with top-left corner on x,y point 1 and bottom-right corner at x,y point 2. Define the overall appearance of the shape by passing a Closure callback as an optional parameter.
 * @method Image reset(string $name = null)                                                                                                   Resets all of the modifications to a state saved previously by backup under an optional name.
 * @method Image resize(int $width = null, int $height = null, \Closure $callback = null)                                                     Resizes current image based on given width and/or height. To contraint the resize command, pass an optional Closure callback as third parameter.
 * @method Image resizeCanvas(int $width, int $height, string $anchor = null, boolean $relative = false, mixed $bgcolor = '#000000')          Resize the boundaries of the current image to given width and height. An anchor can be defined to determine from what point of the image the resizing is going to happen. Set the mode to relative to add or subtract the given width or height to the actual image dimensions. You can also pass a background color for the emerging area of the image.
 * @method Image response(string $format = null, int $quality = 90)                                                                           Sends HTTP response with current image in given format and quality.
 * @method Image rotate(float $angle, string $bgcolor = '#000000')                                                                            Rotate the current image counter-clockwise by a given angle. Optionally define a background color for the uncovered zone after the rotation.
 * @method Image sharpen(int $amount = 10)                                                                                                    Sharpen current image with an optional amount. Use values between 0 and 100.
 * @method Image text(string $text, int $x = 0, int $y = 0, \Closure $callback = null)                                                        Write a text string to the current image at an optional x,y basepoint position. You can define more details like font-size, font-file and alignment via a callback as the fourth parameter.
 * @method Image trim(string $base = 'top-left', array $away = array('top', 'bottom', 'left', 'right'), int $tolerance = 0, int $feather = 0) Trim away image space in given color. Define an optional base to pick a color at a certain position and borders that should be trimmed away. You can also set an optional tolerance level, to trim similar colors and add a feathering border around the trimed image.
 * @method Image widen(int $width, \Closure $callback = null)                                                                                 Resizes the current image to new width, constraining aspect ratio. Pass an optional Closure callback as third parameter, to apply additional constraints like preventing possible upsizing.
 */
class Image extends File
{
    /**
     * Instance of current image driver
     *
     * @var AbstractDriver
     */
    protected $driver;

    /**
     * Image resource/object of current image processor
     *
     * @var mixed
     */
    protected $core;

    /**
     * Array of Image resource backups of current image processor
     *
     * @var array
     */
    protected $backups = array();

    /**
     * Last image encoding result
     *
     * @var string
     */
    public $encoded = '';

    /**
     * Creates a new Image instance
     *
     * @param AbstractDriver $driver
     * @param mixed  $core
     */
    public function __construct(AbstractDriver $driver = null, $core = null)
    {
        $this->driver = $driver;
        $this->core = $core;
    }

    /**
     * Magic method to catch all image calls
     * usually any AbstractCommand
     *
     * @param  string $name
     * @param  Array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $command = $this->driver->executeCommand($this, $name, $arguments);
        return $command->hasOutput() ? $command->getOutput() : $this;
    }

    /**
     * Starts encoding of current image
     *
     * @param  string  $format
     * @param  integer $quality
     * @return \Intervention\Image\Image
     */
    public function encode($format = null, $quality = 90)
    {
        return $this->driver->encode($this, $format, $quality);
    }

    /**
     * Saves encoded image in filesystem
     *
     * @param  string  $path
     * @param  integer $quality
     * @return \Intervention\Image\Image
     */
    public function save($path = null, $quality = null)
    {
        $path = is_null($path) ? $this->basePath() : $path;

        if (is_null($path)) {
            throw new Exception\NotWritableException(
                "Can't write to undefined path."
            );
        }

        $data = $this->encode(pathinfo($path, PATHINFO_EXTENSION), $quality);
        $saved = @file_put_contents($path, $data);

        if ($saved === false) {
            throw new Exception\NotWritableException(
                "Can't write image data to path ({$path})"
            );
        }

        // set new file info
        $this->setFileInfoFromPath($path);

        return $this;
    }

    /**
     * Runs a given filter on current image
     *
     * @param  FiltersFilterInterface $filter
     * @return \Intervention\Image\Image
     */
    public function filter(Filters\FilterInterface $filter)
    {
        return $filter->applyFilter($this);
    }

    /**
     * Returns current image driver
     *
     * @return \Intervention\Image\AbstractDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Sets current image driver
     * @param AbstractDriver $driver
     */
    public function setDriver(AbstractDriver $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Returns current image resource/obj
     *
     * @return mixed
     */
    public function getCore()
    {
        return $this->core;
    }

    /**
     * Sets current image resource
     *
     * @param mixed $core
     */
    public function setCore($core)
    {
        $this->core = $core;

        return $this;
    }

    /**
     * Returns current image backup
     *
     * @param string $name
     * @return mixed
     */
    public function getBackup($name = null)
    {
        $name = is_null($name) ? 'default' : $name;

        if ( ! $this->backupExists($name)) {
            throw new \Intervention\Image\Exception\RuntimeException(
                "Backup with name ({$name}) not available. Call backup() before reset()."
            );
        }

        return $this->backups[$name];
    }

    /**
     * Sets current image backup
     *
     * @param mixed  $resource
     * @param string $name
     * @return self
     */
    public function setBackup($resource, $name = null)
    {
        $name = is_null($name) ? 'default' : $name;

        $this->backups[$name] = $resource;

        return $this;
    }

    /**
     * Checks if named backup exists
     *
     * @param  string $name
     * @return bool
     */
    private function backupExists($name)
    {
        return array_key_exists($name, $this->backups);
    }

    /**
     * Checks if current image is already encoded
     *
     * @return boolean
     */
    public function isEncoded()
    {
        return ! is_null($this->encoded);
    }

    /**
     * Returns encoded image data of current image
     *
     * @return string
     */
    public function getEncoded()
    {
        return $this->encoded;
    }

    /**
     * Sets encoded image buffer
     *
     * @param string $value
     */
    public function setEncoded($value)
    {
        $this->encoded = $value;

        return $this;
    }

    /**
     * Calculates current image width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->getSize()->width;
    }

    /**
     * Alias of getWidth()
     *
     * @return integer
     */
    public function width()
    {
        return $this->getWidth();
    }

    /**
     * Calculates current image height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->getSize()->height;
    }

    /**
     * Alias of getHeight
     *
     * @return integer
     */
    public function height()
    {
        return $this->getHeight();
    }

    /**
     * Reads mime type
     *
     * @return string
     */
    public function mime()
    {
        return $this->mime;
    }

    /**
     * Get fully qualified path to image
     *
     * @return string
     */
    public function basePath()
    {
        if ($this->dirname && $this->basename) {
            return ($this->dirname .'/'. $this->basename);
        }

        return null;
    }

    /**
     * Returns encoded image data in string conversion
     *
     * @return string
     */
    public function __toString()
    {
        return $this->encoded;
    }

    /**
     * Cloning an image
     */
    public function __clone()
    {
        $this->core = $this->driver->cloneCore($this->core);
    }
}
