<?php

namespace Intervention\Image;

/**
 * @method \Intervention\Image\Image backup(string $name = 'default') Backup current image.
 * @method \Intervention\Image\Image blur(integer $amount = 1) Apply gaussian blur filter.
 * @method \Intervention\Image\Image brightness(integer $level) Change image brightness.
 * @method \Intervention\Image\Image circle(integer $radius, integer $x, integer $y, Closure $callback = null) Draw a circle.
 * @method \Intervention\Image\Image colorize(integer $red, integer $green, integer $blue) Change color balance.
 * @method \Intervention\Image\Image contrast(integer $level) Change image contrast.
 * @method \Intervention\Image\Image crop(integer $width, integer $height, integer $x = null, integer $y = null) Crop image.
 * @method void destroy() Destroy instance and free up memory.
 * @method \Intervention\Image\Image ellipse(integer $width, integer $height, integer $x, integer $y, Closure $callback = null) Draw an ellipse.
 * @method mixed exif(string $key = null) Read Exif data from image.
 * @method \Intervention\Image\Image fill(mixed $filling, integer $x = null, integer $y = null) Fill image with color or pattern.
 * @method \Intervention\Image\Image filter(\Intervention\Image\Filters\FilterInterface $filter) Apply custom filter.
 * @method \Intervention\Image\Image flip(mixed $mode = 'h') Mirror an image.
 * @method \Intervention\Image\Image fit(integer $width, integer $height = null, Closure $callback = null, string $position = 'center') Crop and resize combined.
 * @method \Intervention\Image\Image gamma(float $correction) Apply gamma correction.
 * @method \Intervention\Image\Image greyscale() Turn image into greyscale version.
 * @method integer height() Get height of image.
 * @method \Intervention\Image\Image heighten(integer $height, Closure $callback = null) Resize image proportionally to given height.
 * @method \Intervention\Image\Image insert(mixed $source, string $position = 'top-left', integer $x = 0, integer $y = 0) Insert another image.
 * @method \Intervention\Image\Image interlace(boolean $interlace = true) Toggle interlaced image mode.
 * @method \Intervention\Image\Image invert() Invert colors of an image.
 * @method \Intervention\Image\Image limitColors(integer $count, mixed $matte = null) Convert color palette of image to maximum number of colors.
 * @method \Intervention\Image\Image line(integer $x1, integer $y1, integer $x2, integer $y2, Closure $callback = null) Draw a line.
 * @method \Intervention\Image\Image mask(mixed $source, bool $mask_with_alpha = false) Apply alpha mask.
 * @method \Intervention\Image\Image opacity(integer $transparency) Set opacity of an image.
 * @method \Intervention\Image\Image orientate() Adjust image orientation automatically.
 * @method mixed pickColor(integer $x, integer $y, string $format = 'array') Pick a color out of an image.
 * @method \Intervention\Image\Image pixel(mixed $color, integer $x, integer $y) Draw a single pixel.
 * @method \Intervention\Image\Image pixelate(integer $size) Apply pixelation effect.
 * @method \Intervention\Image\Image polygon(array $points, Closure $callback = null) Draw a polygon.
 * @method \Intervention\Image\Image rectangle(integer $x1, integer $y1, integer $x2, integer $y2, Closure $callback = null) Draw a rectangle.
 * @method \Intervention\Image\Image reset(string $name = 'default') Reset image instance to backup.
 * @method \Intervention\Image\Image resize(integer $width, integer $height, Closure $callback = null) Resize image.
 * @method \Intervention\Image\Image resizeCanvas(integer $width, integer $height, string $anchor = 'center', boolean $relative = false, mixed $bgcolor = '#000000') Resize image boundaries.
 * @method mixed response(string $format, integer $quality) Attach image to new HTTP response.
 * @method \Intervention\Image\Image rotate(float $angle, string $bgcolor = '#000000') Rotate image.
 * @method \Intervention\Image\Image sharpen(integer $amount = 10) Sharpen image.
 * @method \Intervention\Image\Image text(string $text, integer $x = 0, integer $y = 0, Closure $callback = null) Write text to an image.
 * @method \Intervention\Image\Image trim(string $base = 'top-left', array $away = null, integer $tolerance = 0, integer $feather = 0) Trim away parts of an image.
 * @method \Intervention\Image\Image widen(integer $width, Closure $callback = null) Resize image proportionally to given width.
 * @method integer width() Get width of image.
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
