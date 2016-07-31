<?php

use Intervention\Image\ImageManager;
use Intervention\Image\Gd\Commands\CompareCommand as CompareGd;
use Intervention\Image\Imagick\Commands\CompareCommand as CompareImagick;

class CompareCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGdStandardComparison()
    {
        $imageManager = new ImageManager(array('driver' => 'gd'));
        $image = $imageManager->make(__DIR__.'/images/compare_old.jpg');
        $otherImage = $imageManager->make(__DIR__.'/images/compare_new.jpg');

        $command = new CompareGd(array($otherImage));
        $result = $command->execute($image);
        $comparison = $command->getOutput();

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $comparison);
        $this->assertEquals(157, $comparison->getScore());
        $this->assertInstanceOf('Intervention\Image\Image', $comparison->getDiffImage());
    }

    public function testGdNoThresholdComparison()
    {
        $imageManager = new ImageManager(array('driver' => 'gd'));
        $image = $imageManager->make(__DIR__.'/images/compare_old.jpg');
        $otherImage = $imageManager->make(__DIR__.'/images/compare_new.jpg');

        $command = new CompareGd(array($otherImage, 0));
        $result = $command->execute($image);
        $comparison = $command->getOutput();

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $comparison);
        $this->assertEquals(22699, $comparison->getScore());
        $this->assertInstanceOf('Intervention\Image\Image', $comparison->getDiffImage());
    }

    public function testGdResizeComparison()
    {
        $imageManager = new ImageManager(array('driver' => 'gd'));
        $image = $imageManager->make(__DIR__.'/images/compare_old.jpg');
        $otherImage = $imageManager->make(__DIR__.'/images/compare_small.jpg');

        $command = new CompareGd(array($otherImage));
        $result = $command->execute($image);
        $comparison = $command->getOutput();

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $comparison);
        $this->assertEquals(5057, $comparison->getScore());
        $this->assertInstanceOf('Intervention\Image\Image', $comparison->getDiffImage());
    }

    public function testImagickStandardComparison()
    {
        $imageManager = new ImageManager(array('driver' => 'imagick'));
        $image = $imageManager->make(__DIR__.'/images/compare_old.jpg');
        $otherImage = $imageManager->make(__DIR__.'/images/compare_new.jpg');

        $command = new CompareImagick(array($otherImage));
        $result = $command->execute($image);
        $comparison = $command->getOutput();

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $comparison);
        $this->assertEquals(172.0, $comparison->getScore());
        $this->assertInstanceOf('Intervention\Image\Image', $comparison->getDiffImage());
    }

    public function testImagickNoThresholdComparison()
    {
        $imageManager = new ImageManager(array('driver' => 'imagick'));
        $image = $imageManager->make(__DIR__.'/images/compare_old.jpg');
        $otherImage = $imageManager->make(__DIR__.'/images/compare_new.jpg');

        $command = new CompareImagick(array($otherImage, 0));
        $result = $command->execute($image);
        $comparison = $command->getOutput();

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $comparison);
        $this->assertEquals(22665.0, $comparison->getScore());
        $this->assertInstanceOf('Intervention\Image\Image', $comparison->getDiffImage());
    }

    public function testImagickResizeComparison()
    {
        $imageManager = new ImageManager(array('driver' => 'imagick'));
        $image = $imageManager->make(__DIR__.'/images/compare_old.jpg');
        $otherImage = $imageManager->make(__DIR__.'/images/compare_small.jpg');

        $command = new CompareImagick(array($otherImage));
        $result = $command->execute($image);
        $comparison = $command->getOutput();

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $comparison);
        $this->assertEquals(9992.0, $comparison->getScore());
        $this->assertInstanceOf('Intervention\Image\Image', $comparison->getDiffImage());
    }
}
