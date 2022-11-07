<?php

namespace Intervention\Image;

class File
{
    /**
     * Mime type
     *
     * @var string|null
     */
    public $mime;

    /**
     * Name of directory path
     *
     * @var string|null
     */
    public $dirname;

    /**
     * Basename of current file
     *
     * @var string|null
     */
    public $basename;

    /**
     * File extension of current file
     *
     * @var string|null
     */
    public $extension;

    /**
     * File name of current file
     *
     * @var string|null
     */
    public $filename;

    /**
     * Resource from which the image was created
     *
     * @var resource|null
     */
    public $stream;

    /**
     * Sets all instance properties from given path
     *
     * @param string $path
     */
    public function setFileInfoFromPath($path)
    {
        $info = pathinfo($path);
        $this->dirname = array_key_exists('dirname', $info) ? $info['dirname'] : null;
        $this->basename = array_key_exists('basename', $info) ? $info['basename'] : null;
        $this->extension = array_key_exists('extension', $info) ? $info['extension'] : null;
        $this->filename = array_key_exists('filename', $info) ? $info['filename'] : null;

        if (file_exists($path) && is_file($path)) {
            $this->mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        }

        return $this;
    }

    /**
     * Keep a copy of the original stream resource.  We can use it to read EXIF data later.
     *
     * @param resource|StreamInterface $stream
     */
    public function setOriginalStream($stream)
    {
        $this->stream = $stream;

        return $this;
    }

     /**
      * Get file size
      * 
      * @return mixed
      */
    public function filesize()
    {
        $path = $this->basePath();

        if (file_exists($path) && is_file($path)) {
            return filesize($path);
        }
        
        return false;
    }

    /**
     * Get fully qualified path
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

}
