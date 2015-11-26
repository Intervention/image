<?php

use Intervention\Image\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    public function testSetFileInfoFromPath()
    {
        $file = new File;
        $file->setFileInfoFromPath('tests/images/test.jpg');
        $this->assertEquals('tests/images', $file->dirname);
        $this->assertEquals('test.jpg', $file->basename);
        $this->assertEquals('jpg', $file->extension);
        $this->assertEquals('test', $file->filename);
        $this->assertEquals('image/jpeg', $file->mime);
    }

    public function testBasePath()
    {
        $file = new File;
        $this->assertNull(null, $file->basePath());

        $file->dirname = 'foo';
        $file->basename = 'bar';
        $this->assertEquals('foo/bar', $file->basePath());
    }
}
