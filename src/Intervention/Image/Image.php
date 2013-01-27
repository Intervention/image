<?php

namespace Intervention\Image;

use Exception;
use Illuminate\Filesystem\Filesystem;

class Image
{
    public $resource;
    public $type;
    public $width;
    public $height;
    
    public $dirname;
    public $basename;
    public $extension;
    public $filename;

    protected $filesystem;
    
    public function __construct($path = null) 
    {
        $this->filesystem = new Filesystem;
        $this->setProperties($path);
    }

    public static function make($path)
    {
        return new Image($path);
    }

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

        // dd(array($grab_width, $grab_height));

        return $this->modify(0, 0, $src_x, $src_y, $width, $height, $grab_width, $grab_height);
    }

    public function insert($file, $pos_x = 0, $pos_y = 0)
    {
        $obj = is_a($file, 'Intervention\Image') ? $file : (new Image($file));
        imagecopy($this->resource, $obj->resource, $pos_x, $pos_y, 0, 0, $obj->width, $obj->height);

        return $this;
    }

    public function pixelate($size = 10, $advanced = true)
    {
        imagefilter($this->resource, IMG_FILTER_PIXELATE, $size, $advanced);
    }

    public function reset()
    {   
        $this->setProperties($this->dirname .'/'. $this->basename);
    }

    private function data($type = null)
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
                @imagejpeg($this->resource, null, 90);
            break;
        }

        $data = ob_get_contents();
        
        ob_end_clean();
        return $data;
    }

    public function save($path = null)
    {
        $path = is_null($path) ? ($this->dirname .'/'. $this->basename) : $path;
        file_put_contents($path, $this->data($this->filesystem->extension($path)));
        return $this;
    }

    /*
    public function render($type = 'jpg')
    {
        return Response::make($this->data($type), 200, 
                array('content-type' => $this->filesystem->mime($type)));
    }
    */

    public function getFilesystem()
    {
        return $this->filesystem;
    }

    public function __toString()
    {
        return $this->data();
    }
}