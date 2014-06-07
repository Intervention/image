<?php

use Intervention\Image\Commands\OrientateCommand;

class OrientateCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testExecuteOrientationTwo()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(2);
        $image->shouldReceive('flip')->once();
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testExecuteOrientationThree()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(3);
        $image->shouldReceive('rotate')->with(180)->once();
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testExecuteOrientationFour()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(4);
        $image->shouldReceive('rotate')->with(180)->once()->andReturn($image);
        $image->shouldReceive('flip')->once();
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testExecuteOrientationFive()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(5);
        $image->shouldReceive('rotate')->with(270)->once()->andReturn($image);
        $image->shouldReceive('flip')->once();
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testExecuteOrientationSix()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(6);
        $image->shouldReceive('rotate')->with(270)->once();
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testExecuteOrientationSeven()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(7);
        $image->shouldReceive('rotate')->with(90)->once()->andReturn($image);
        $image->shouldReceive('flip')->once();
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testExecuteOrientationEight()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(8);
        $image->shouldReceive('rotate')->with(90)->once();
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testExecuteOrientationNoExifData()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('exif')->with('Orientation')->once()->andReturn(null);
        $command = new OrientateCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
