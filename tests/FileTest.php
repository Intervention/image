<?php

use Intervention\Image\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    public function testSetFileInfoFromPath()
    {
        $file = new File;
        $file->setFileInfoFromPath('test/foo/bar.baz');
        $this->assertEquals('test/foo', $file->dirname);
        $this->assertEquals('bar.baz', $file->basename);
        $this->assertEquals('baz', $file->extension);
        $this->assertEquals('bar', $file->filename);
    }
}
