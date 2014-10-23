<?php

namespace Intervention\Image;

class File
{
    /**
     * Mime type
     *
     * @var string
     */
    public $mime;

    /**
     * Name of directory path
     *
     * @var string
     */
    public $dirname;

    /**
     * Basename of current file
     *
     * @var string
     */
    public $basename;

    /**
     * File extension of current file
     *
     * @var string
     */
    public $extension;

    /**
     * File name of current file
     *
     * @var string
     */
    public $filename;

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
     * Sets all instance properties from given SplFileInfo object
     *
     * @param SplFileInfo $object
     */
    public function setFileInfoFromSplFileInfo(\SplFileInfo $object)
    {
        $this->dirname = $object->getPath();
        $this->basename = $object->getBasename();
        $this->extension = $object->getExtension();
        $this->filename = $object->getFilename();

        if ($object->getType() === 'file') {
            $this->mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $object->getRealPath());
        }

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

}
