<?php

use Intervention\Image\Commands\Argument;

class ArgumentTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')));
        $this->validateArgument($arg, 'foo');

        $arg = new Argument($this->getMockedCommand(array('foo', 'bar')), 1);
        $this->validateArgument($arg, 'bar');

        $arg = new Argument($this->getMockedCommand(), 0);
        $this->validateArgument($arg, null);
    }

    public function testRequiredPass()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')));
        $arg->required();
        $this->validateArgument($arg, 'foo');

        $arg = new Argument($this->getMockedCommand(array(null)));
        $arg->required();
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(array(0)));
        $arg->required();
        $this->validateArgument($arg, 0);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testRequiredFail()
    {
        $arg = new Argument($this->getMockedCommand(array()));
        $arg->required();
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testRequiredFailSecondParameter()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')), 1);
        $arg->required();
    }

    public function testTypeIntegerPass()
    {
        $arg = new Argument($this->getMockedCommand(array()));
        $arg->type('integer');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(array(123)));
        $arg->type('integer');
        $this->validateArgument($arg, 123);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testTypeIntegerFail()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')));
        $arg->type('integer');
    }

    public function testTypeNumericPass()
    {
        $arg = new Argument($this->getMockedCommand(array()));
        $arg->type('numeric');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(array(12.3)));
        $arg->type('numeric');
        $this->validateArgument($arg, 12.3);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testTypeNumericFail()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')));
        $arg->type('numeric');
    }

    public function testTypeBooleanPass()
    {
        $arg = new Argument($this->getMockedCommand(array()));
        $arg->type('boolean');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(array(true)));
        $arg->type('boolean');
        $this->validateArgument($arg, true);

        $arg = new Argument($this->getMockedCommand(array(false)));
        $arg->type('boolean');
        $this->validateArgument($arg, false);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testTypeBooleanFail()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')));
        $arg->type('boolean');
    }

    public function testTypeStringPass()
    {
        $arg = new Argument($this->getMockedCommand(array()));
        $arg->type('string');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(array('foo')));
        $arg->type('string');
        $this->validateArgument($arg, 'foo');
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testTypeStringFail()
    {
        $arg = new Argument($this->getMockedCommand(array(12)));
        $arg->type('string');
    }

    public function testTypeClosurePass()
    {
        $arg = new Argument($this->getMockedCommand(array()));
        $arg->type('closure');
        $this->validateArgument($arg, null);

        $c = function ($foo) {};
        $arg = new Argument($this->getMockedCommand(array($c)));
        $arg->type('closure');
        $this->validateArgument($arg, $c);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testTypeClosureFail()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')));
        $arg->type('closure');
    }

    public function testBetweenPass()
    {
        $arg = new Argument($this->getMockedCommand(array()));
        $arg->between(0, 10);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(array(null)));
        $arg->between(0, 10);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(array(4.5)));
        $arg->between(0, 10);
        $this->validateArgument($arg, 4.5);

        $arg = new Argument($this->getMockedCommand(array(4.5)));
        $arg->between(10, 1);
        $this->validateArgument($arg, 4.5);

        $arg = new Argument($this->getMockedCommand(array(0)));
        $arg->between(0, 10);
        $this->validateArgument($arg, 0);

        $arg = new Argument($this->getMockedCommand(array(10)));
        $arg->between(0, 10);
        $this->validateArgument($arg, 10);

        $arg = new Argument($this->getMockedCommand(array(0)));
        $arg->between(-100, 100);
        $this->validateArgument($arg, 0);

        $arg = new Argument($this->getMockedCommand(array(-100)));
        $arg->between(-100, 100);
        $this->validateArgument($arg, -100);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testBetweenFailString()
    {
        $arg = new Argument($this->getMockedCommand(array('foo')));
        $arg->between(1, 10);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testBetweenFailAbove()
    {
        $arg = new Argument($this->getMockedCommand(array(10.9)));
        $arg->between(0, 10);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testBetweenFailBelow()
    {
        $arg = new Argument($this->getMockedCommand(array(-1)));
        $arg->between(0, 10);
    }

    /**
     * @expectedException \Intervention\Image\Exception\InvalidArgumentException
     */
    public function testBetweenFail()
    {
        $arg = new Argument($this->getMockedCommand(array(-1000)));
        $arg->between(-100, 100);
    }

    private function validateArgument($argument, $value)
    {
        $this->assertInstanceOf('\Intervention\Image\Commands\Argument', $argument);
        $this->assertEquals($value, $argument->value());
    }

    private function getMockedCommand($arguments = array())
    {
        return $this->getMockForAbstractClass('\Intervention\Image\Commands\AbstractCommand', array($arguments));
    }
}
