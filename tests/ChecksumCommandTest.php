<?php

use Intervention\Image\Commands\ChecksumCommand;

class ChecksumCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testExecute()
    {
        $size = Mockery::mock('Intervention\Image\Size', array(3, 3));
        $color = array(0,0,0,1);
        $resource = imagecreatefrompng(__DIR__.'/images/tile.png');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $image->shouldReceive('pickColor')->times(9)->andReturn($color);
        $command = new ChecksumCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals('ec9cbdb71be04e26b4a89333f20c273b', $command->getOutput());
    }
}
