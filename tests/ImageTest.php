<?php

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetCore()
    {
        $image = $this->getTestImage();
        $this->assertEquals('mock', $image->getCore());
    }

    public function testCommandCall()
    {
        $image = $this->getTestImage();
        $result = $image->test(1, 2, 3);
        $this->assertEquals('mock', $result);
    }

    public function testEncode()
    {
        $image = $this->getTestImage();
        $image->getDriver()->shouldReceive('encode')->with($image, 'png', 90)->once();
        $image->encode('png', 90);
    }

    public function testSave()
    {
        $save_as = __DIR__.'/tmp/test.jpg';
        $image = $this->getTestImage();
        $image->getDriver()->shouldReceive('encode')->with($image, 'jpg', 85)->once()->andReturn('mock');
        $image = $image->save($save_as, 85);
        $this->assertInstanceOf('\Intervention\Image\Image', $image);
        $this->assertFileExists($save_as);
        $this->assertEquals($image->basename, 'test.jpg');
        $this->assertEquals($image->extension, 'jpg');
        $this->assertEquals($image->filename, 'test');
        @unlink($save_as);
    }

    public function testFormatSave()
    {
        $save_as = __DIR__.'/tmp/test';

        $config = ['driver' => new Intervention\Image\Imagick\Driver()];
        $manager = new ImageManager($config);

        $image = $manager->make('data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $this->assertInstanceOf('Intervention\Image\Image', $image);
        $this->assertInstanceOf('Imagick', $image->getCore());

        $gifCore = $image->getCore();
        $this->assertEquals($gifCore->getImageMimeType(), 'image/gif');
        $image->save($save_as, null, 'jpg');

        $this->assertEquals(\mime_content_type($save_as), 'image/jpeg');
        @unlink($save_as);
    }

    public function testIsEncoded()
    {
        $image = $this->getTestImage();
        $this->assertFalse($image->isEncoded());

        $image->setEncoded('foo');
        $this->assertTrue($image->isEncoded());
    }

    public function testFilter()
    {
        $demoFilter = Mockery::mock('\Intervention\Image\Filters\DemoFilter', [15]);
        $image = $this->getTestImage();
        $demoFilter->shouldReceive('applyFilter')->with($image)->once()->andReturn($image);
        $image->filter($demoFilter);
    }

    public function testMime()
    {
        $image = $this->getTestImage();
        $this->assertEquals('image/png', $image->mime());
    }

    /**
     * @expectedException \Intervention\Image\Exception\RuntimeException
     */
    public function testGetBackupWithoutBackuo()
    {
        $image = $this->getTestImage();
        $image->getBackup();
    }

    public function testSetGetBackup()
    {
        $image = $this->getTestImage();
        $image->setBackup('foo');
        $backup = $image->getBackup();
        $this->assertEquals('foo', $backup);
    }

    public function testGetBackups()
    {
        $image = $this->getTestImage();
        $backups = $image->getBackups();
        $this->assertEquals([], $backups);

        $image = $this->getTestImage();
        $image->setBackup('foo');
        $image->setBackup('bar');
        $image->setBackup('baz');
        $backups = $image->getBackups();
        $this->assertEquals(['default' => 'baz'], $backups);

        $image = $this->getTestImage();
        $image->setBackup('foo', 'a');
        $image->setBackup('bar', 'b');
        $image->setBackup('baz', 'c');
        $backups = $image->getBackups();
        $this->assertEquals(['a' => 'foo', 'b' => 'bar', 'c' => 'baz'], $backups);
    }

    private function getTestImage()
    {
        $size = Mockery::mock('\Intervention\Image\Size', [800, 600]);
        $driver = Mockery::mock('\Intervention\Image\AbstractDriver');
        $command = Mockery::mock('\Intervention\Image\Commands\AbstractCommand');
        $command->shouldReceive('hasOutput')->andReturn(true);
        $command->shouldReceive('getOutput')->andReturn('mock');
        $driver->shouldReceive('executeCommand')->andReturn($command);
        $image = new Image($driver, 'mock');
        $image->mime = 'image/png';
        $image->dirname = './tmp';
        $image->basename = 'foo.png';

        return $image;
    }
}
