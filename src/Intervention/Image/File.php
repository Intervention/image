<?php

namespace Intervention\Image;

class File
{
    public $mime;
    public $dirname;
    public $basename;
    public $extension;
    public $filename;

    public function setFileInfoFromPath($path)
    {
        $info = pathinfo($path);
        $this->dirname = array_key_exists('dirname', $info) ? $info['dirname'] : null;
        $this->basename = array_key_exists('basename', $info) ? $info['basename'] : null;
        $this->extension = array_key_exists('extension', $info) ? $info['extension'] : null;
        $this->filename = array_key_exists('filename', $info) ? $info['filename'] : null;

        return $this;
    }
}
