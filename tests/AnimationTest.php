<?php

use Intervention\Image\Animation;

class AnimationTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testConstructor()
    {
        $animation = new Animation;
        $this->assertInstanceOf('Intervention\Image\Animation', $animation);
        $this->assertNull($animation->loops);
    }

    public function testConstructorWithParameters()
    {
        $animation = new Animation(12);
        $this->assertInstanceOf('Intervention\Image\Animation', $animation);
        $this->assertEquals(12, $animation->loops);
    }

    public function testIterate()
    {
        $frame = Mockery::mock('Intervention\Image\Frame');
        $animation = new Animation;
        $animation->addFrame($frame);
        $animation->addFrame($frame);
        $animation->addFrame($frame);
        $counter = 0;
        foreach ($animation as $frame) {
            $counter++;
        }
        $this->assertEquals(3, $counter);
    }

    public function testSetLoops()
    {
        $animation = new Animation(12);
        $animation->setLoops(13);
        $this->assertEquals(13, $animation->loops);
    }

    public function testAddFrame()
    {
        $frame = Mockery::mock('Intervention\Image\Frame');
        $animation = new Animation;
        $animation->addFrame($frame);
        $this->assertHasFrames(1, $animation);
        $animation->addFrame($frame);
        $this->assertHasFrames(2, $animation);
    }

    public function testAddFrames()
    {
        $frame = Mockery::mock('Intervention\Image\Frame');
        $animation = new Animation;
        $animation->addFrames(array($frame, $frame));
        $this->assertHasFrames(2, $animation);
        $animation->addFrames(array($frame, $frame, $frame));
        $this->assertHasFrames(5, $animation);
        $animation->addFrames(array($frame, $frame));
        $this->assertHasFrames(7, $animation);
    }

    public function testSetFrames()
    {
        $frame = Mockery::mock('Intervention\Image\Frame');
        $animation = new Animation;
        $animation->setFrames(array($frame, $frame, $frame));
        $this->assertHasFrames(3, $animation);
    }    

    public function testGetFrames()
    {
        $animation = new Animation;
        $this->assertEquals(array(), $animation->getFrames());
    }

    private function assertHasFrames($number, $animation)
    {
        $this->assertEquals($number, count($animation->getFrames()));
    }

}