<?php

namespace Intervention\Image;

use Exception;
use Illuminate\Filesystem\Filesystem;

class Image
{
    /**
     * The image resource identifier of current image
     * @var resource
     */
    public $resource;

    /**
     * Type of current image
     * @var string
     */
    public $type;

    /**
     * Width of current image
     * @var integer
     */
    public $width;

    /**
     * Height of current image
     * @var integer
     */
    public $height;
    
    /**
     * Directory path of current image
     * @var string
     */
    public $dirname;

    /**
     * Trailing name component of current image filename
     * @var string
     */
    public $basename;

    /**
     * File extension of current image filename
     * @var string
     */
    public $extension;

    /**
     * Combined filename (basename and extension)
     * @var [type]
     */
    public $filename;

    /**
     * Instance of Illuminate\Filesystem\Filesystem
     * @var Filesystem
     */
    protected $filesystem;
    
    /**
     * Create a new instance of Image class
     * 
     * @param string $path
     */
    public function __construct($path = null) 
    {
        $this->filesystem = new Filesystem;
        $this->setProperties($path);
    }

    /**
     * Create a new image resource from image file
     * 
     * @param  string $path
     * @return Image
     */
    public static function make($path)
    {
        return new Image($path);
    }

    /**
     * Set local properties for image resource
     * 
     * @param string $path
     */
    private function setProperties($path)
    {
        if ( ! is_null($path) && $this->filesystem->exists($path)) {
            
            // set file info
            $info = pathinfo($path);
            $this->dirname = $info['dirname'];
            $this->basename = $info['basename'];
            $this->extension = $info['extension'];
            $this->filename = $info['filename'];

            // set image info
            list($this->width, $this->height, $this->type) = @getimagesize($path);

            // set resource
            switch ($this->type) {
                case IMG_PNG:
                case 3:
                    $this->resource = @imagecreatefrompng($path);
                    break;
                
                case IMG_JPG:
                    $this->resource = @imagecreatefromjpeg($path);
                    break;

                case IMG_GIF:
                    $this->resource = @imagecreatefromgif($path);
                    break;

                default:
                    throw new Exception("Wrong image type ({$this->type}) only use JPG, PNG or GIF images.");
                    break;
            }
        } else {
            
            $this->width = 1;
            $this->height = 1;
            $this->resource = @imagecreatetruecolor($this->width, $this->height);
        }

        @imagealphablending($this->resource, false);
        @imagesavealpha($this->resource, true);
    }
    
    /**
     * Modify wrapper function used by resize and grab
     * 
     * @param  integer $dst_x
     * @param  integer $dst_y
     * @param  integer $src_x
     * @param  integer $src_y
     * @param  integer $dst_w
     * @param  integer $dst_h
     * @param  integer $src_w
     * @param  integer $src_h
     * @return Image
     */
    private function modify($dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h)
    {
        // create new image
        $image = @imagecreatetruecolor($dst_w, $dst_h);

        // copy content from resource        
        @imagecopyresampled($image, $this->resource, $dst_x , $dst_y , $src_x , $src_y , $dst_w , $dst_h , $src_w , $src_h);

        // set new content as recource
        $this->resource = $image;

        // set new dimensions
        $this->width = $dst_w;
        $this->height = $dst_h;

        return $this;
    }

    /**
     * Resize current image based on given width/height
     * 
     * @param  mixed width|height  width and height are optional, the not given 
     *                             parameter is calculated based on the given
     * @return Image
     */
    public function resize()
    {
        $args = func_get_args();
        
        if (array_key_exists(0, $args) && is_array($args[0])) {
            
            // extract 'width' and 'height'            
            extract(array_only($args[0], array('width', 'height')));
            $width = isset($width) ? intval($width) : null;
            $height = isset($height) ? intval($height) : null;
            
            if ( ! is_null($width) OR ! is_null($height)) {
                // if width or height are not set, define values automatically
                $width = is_null($width) ? intval($height / $this->height * $this->width) : $width;
                $height = is_null($height) ? intval($width / $this->width * $this->height) : $height;
            } else {
                // width or height not defined (resume with original values)
                throw new Exception('Width or Height needs to be defined as keys in paramater array');
            }

        } elseif (array_key_exists(0, $args) && array_key_exists(1, $args) && is_numeric($args[0]) && is_numeric($args[1])) {
            $width = intval($args[0]);
            $height = intval($args[1]);
        }

        if (is_null($width) OR is_null($height)) {
                throw new Exception('width or height needs to be defined');
        }

        // create new image in new dimensions
        return $this->modify(0, 0, 0, 0, $width, $height, $this->width, $this->height);
    }

    /**
     * Cut out a detail of the image in given ratio and resize to output size
     * 
     * @param mixed width|height    width and height are optional, the not given
     *                              parameter is calculated based on the given
     * @return Image
     */
    public function grab()
    {
        $args = func_get_args();

        if (array_key_exists(0, $args) && is_array($args[0])) {
            // extract 'width' and 'height'            
            extract(array_only($args[0], array('width', 'height')));
            $width = isset($width) ? intval($width) : null;
            $height = isset($height) ? intval($height) : null;
            
            if ( ! is_null($width) OR ! is_null($height)) {
                // if width or height are not set, define values automatically
                $width = is_null($width) ? $height : $width;
                $height = is_null($height) ? $width : $height;
            } else {
                // width or height not defined (resume with original values)
                throw new Exception('Width or Height needs to be defined as keys in paramater array');
            }

        } elseif (array_key_exists(0, $args) && array_key_exists(1, $args) && is_numeric($args[0]) && is_numeric($args[1])) {
            
            $width = intval($args[0]);
            $height = intval($args[1]);

        } elseif (array_key_exists(0, $args) && is_numeric($args[0])) {
            
            $width = intval($args[0]);
            $height = intval($args[0]);

        }

        if (is_null($width) OR is_null($height)) {
                throw new Exception('width or height needs to be defined');
        }

        // ausschnitt berechnen
        $grab_width = $this->width;
        $ratio = $grab_width / $width;

        if($height * $ratio <= $this->height)
        {
            $grab_height = round($height * $ratio);
            $src_x = 0;
            $src_y = round(($this->height - $grab_height) / 2);
        }
        else
        {
            $grab_height = $this->height;
            $ratio = $grab_height / $height;
            $grab_width = round($width * $ratio);
            $src_x = round(($this->width - $grab_width) / 2);
            $src_y = 0;
        }

        return $this->modify(0, 0, $src_x, $src_y, $width, $height, $grab_width, $grab_height);
    }

    /**
     * Insert another image on top of the current image
     * 
     * @param  string  $file
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @return Image
     */
    public function insert($file, $pos_x = 0, $pos_y = 0)
    {
        $obj = is_a($file, 'Intervention\Image') ? $file : (new Image($file));
        imagecopy($this->resource, $obj->resource, $pos_x, $pos_y, 0, 0, $obj->width, $obj->height);

        return $this;
    }

    /**
     * Fill image with given hexadecimal color at position x,y
     * 
     * @param  string  $color
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @return Image
     */
    public function fill($color, $pos_x = 0, $pos_y = 0)
    {
        imagefill($this->resource, $pos_x, $pos_y, $this->getColor($color));
        return $this;
    }

    /**
     * Set single pixel
     * 
     * @param  string  $color
     * @param  integer $pos_x
     * @param  integer $pos_y
     * @return Image
     */
    public function pixel($color, $pos_x = 0, $pos_y = 0)
    {
        imagesetpixel($this->resource, $pos_x, $pos_y, $this->getColor($color));
        return $this;
    }

    /**
     * Write text in current image
     * 
     * @param  string  $text     
     * @param  integer $pos_x   
     * @param  integer $pos_y   
     * @param  integer $angle   
     * @param  integer $size    
     * @param  string  $color   
     * @param  string  $fontfile
     * @return Image            
     */
    public function text($text, $pos_x = 0, $pos_y = 0, $size = 16, $color = '000000', $angle = 0, $fontfile = null)
    {
        if (is_null($fontfile)) {
            
            imagestring($this->resource, $size, $pos_x, $pos_y, $text, $this->getColor($color)); 

        } else {
            
            imagettftext($this->resource, $size, $angle, $pos_x, $pos_y, $this->getColor($color), $fontfile, $text); 

        }

        return $this;
    }

    /**
     * Pixelate current image
     * 
     * @param  integer $size
     * @param  boolean $advanced
     * @return Image            
     */
    public function pixelate($size = 10, $advanced = true)
    {
        imagefilter($this->resource, IMG_FILTER_PIXELATE, $size, $advanced);
        return $this;
    }

    /**
     * Turn current image into a greyscale verision
     * 
     * @return Image
     */
    public function grayscale()
    {
        imagefilter($this->resource, IMG_FILTER_GRAYSCALE);
        return $this;
    }

    /**
     * Alias of greyscale
     * 
     * @return Image
     */
    public function greyscale()
    {
        $this->grayscale();
        return $this;
    }

    /**
     * Reset to original image resource
     * 
     * @return void
     */
    public function reset()
    {   
        $this->setProperties($this->dirname .'/'. $this->basename);
        return $this;
    }

    /**
     * Returns image type stream
     * 
     * @param string $type gif|png|jpg|jpeg
     * @param integer quality
     * @return string
     */
    private function data($type = null, $quality = 90)
    {
        ob_start();

        switch (strtolower($type)) {
            case 'gif':
                @imagegif($this->resource);
            break;

            case 'png':
                @imagepng($this->resource);
            break;

            default:
            case 'jpg':
            case 'jpeg':
                @imagejpeg($this->resource, null, $quality);
            break;
        }

        $data = ob_get_contents();
        
        ob_end_clean();
        return $data;
    }

    /**
     * Allocate color from given string
     * 
     * @param  string $value
     * @return int
     */
    private function getColor($value)
    {
        if (is_array($value)) {

            list($r, $g, $b) = $value;

            $color = array(
                'r' => '0x' . $r,
                'g' => '0x' . $g,
                'b' => '0x' . $b
            );

        } elseif(is_string($value) && strlen($value) == 6) {

            $color = array(
                'r' => '0x' . substr($value, 0, 2),
                'g' => '0x' . substr($value, 2, 2),
                'b' => '0x' . substr($value, 4, 2)
            );
        }

        if (is_array($color)) {
            return imagecolorallocate($this->resource, $color['r'], $color['g'], $color['b']);
        } else {
            throw new Exception("Error Processing Color");
        }
    }

    /**
     * Save image in filesystem
     * 
     * @param  string $path
     * @return Image 
     */
    public function save($path = null, $quality = 90)
    {
        $path = is_null($path) ? ($this->dirname .'/'. $this->basename) : $path;
        file_put_contents($path, $this->data($this->filesystem->extension($path), $quality));
        return $this;
    }

    /*
    public function render($type = 'jpg')
    {
        return Response::make($this->data($type), 200, 
                array('content-type' => $this->filesystem->mime($type)));
    }
    */

    /**
     * Return filesystem object
     * 
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Returns image stream
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->data();
    }
}