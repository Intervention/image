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
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('encode')->once()->andReturn('encoded_image_data');

        $command = new ChecksumCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals(md5('encoded_image_data'), $command->getOutput());
    }
}
