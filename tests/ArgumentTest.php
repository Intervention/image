<?php

use Intervention\Image\Commands\Argument;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    public function testConstructor()
    {
        $arg = new Argument($this->getMockedCommand(['foo']));
        $this->validateArgument($arg, 'foo');

        $arg = new Argument($this->getMockedCommand(['foo', 'bar']), 1);
        $this->validateArgument($arg, 'bar');

        $arg = new Argument($this->getMockedCommand(), 0);
        $this->validateArgument($arg, null);
    }

    public function testRequiredPass()
    {
        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->required();
        $this->validateArgument($arg, 'foo');

        $arg = new Argument($this->getMockedCommand([null]));
        $arg->required();
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([0]));
        $arg->required();
        $this->validateArgument($arg, 0);
    }

    /**
     */
    public function testRequiredFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([]));
        $arg->required();
    }

    /**
     */
    public function testRequiredFailSecondParameter()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']), 1);
        $arg->required();
    }

    public function testTypeIntegerPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->type('integer');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([123]));
        $arg->type('integer');
        $this->validateArgument($arg, 123);
    }

    /**
     */
    public function testTypeIntegerFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->type('integer');
    }

    public function testTypeArrayPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->type('array');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([[1,2,3]]));
        $arg->type('array');
        $this->validateArgument($arg, [1,2,3]);
    }

    /**
     */
    public function testTypeArrayFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->type('array');
    }

    public function testTypeDigitPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->type('digit');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([0]));
        $arg->type('digit');
        $this->validateArgument($arg, 0);

        $arg = new Argument($this->getMockedCommand([123]));
        $arg->type('digit');
        $this->validateArgument($arg, 123);

        $arg = new Argument($this->getMockedCommand([5.0]));
        $arg->type('digit');
        $this->validateArgument($arg, 5.0);

        $arg = new Argument($this->getMockedCommand([-10]));
        $arg->type('digit');
        $this->validateArgument($arg, -10);

        $arg = new Argument($this->getMockedCommand([-10.0]));
        $arg->type('digit');
        $this->validateArgument($arg, -10.0);

        $arg = new Argument($this->getMockedCommand(['12']));
        $arg->type('digit');
        $this->validateArgument($arg, '12');

        $arg = new Argument($this->getMockedCommand(['12.0']));
        $arg->type('digit');
        $this->validateArgument($arg, '12.0');
    }

    /**
     */
    public function testTypeDigitFailString()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->type('digit');
    }

    /**
     */
    public function testTypeDigitFailFloat()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([12.5]));
        $arg->type('digit');
    }

    /**
     */
    public function testTypeDigitFailBool()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([true]));
        $arg->type('digit');
    }

    public function testTypeNumericPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->type('numeric');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([12.3]));
        $arg->type('numeric');
        $this->validateArgument($arg, 12.3);
    }

    /**
     */
    public function testTypeNumericFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->type('numeric');
    }

    public function testTypeBooleanPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->type('boolean');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([true]));
        $arg->type('boolean');
        $this->validateArgument($arg, true);

        $arg = new Argument($this->getMockedCommand([false]));
        $arg->type('boolean');
        $this->validateArgument($arg, false);
    }

    /**
     */
    public function testTypeBooleanFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->type('boolean');
    }

    public function testTypeStringPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->type('string');
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->type('string');
        $this->validateArgument($arg, 'foo');
    }

    /**
     */
    public function testTypeStringFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([12]));
        $arg->type('string');
    }

    public function testTypeClosurePass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->type('closure');
        $this->validateArgument($arg, null);

        $c = function ($foo) {};
        $arg = new Argument($this->getMockedCommand([$c]));
        $arg->type('closure');
        $this->validateArgument($arg, $c);
    }

    /**
     */
    public function testTypeClosureFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->type('closure');
    }

    public function testBetweenPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->between(0, 10);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([null]));
        $arg->between(0, 10);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([4.5]));
        $arg->between(0, 10);
        $this->validateArgument($arg, 4.5);

        $arg = new Argument($this->getMockedCommand([4.5]));
        $arg->between(10, 1);
        $this->validateArgument($arg, 4.5);

        $arg = new Argument($this->getMockedCommand([0]));
        $arg->between(0, 10);
        $this->validateArgument($arg, 0);

        $arg = new Argument($this->getMockedCommand([10]));
        $arg->between(0, 10);
        $this->validateArgument($arg, 10);

        $arg = new Argument($this->getMockedCommand([0]));
        $arg->between(-100, 100);
        $this->validateArgument($arg, 0);

        $arg = new Argument($this->getMockedCommand([-100]));
        $arg->between(-100, 100);
        $this->validateArgument($arg, -100);
    }

    /**
     */
    public function testBetweenFailString()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->between(1, 10);
    }

    /**
     */
    public function testBetweenFailAbove()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([10.9]));
        $arg->between(0, 10);
    }

    /**
     */
    public function testBetweenFailBelow()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([-1]));
        $arg->between(0, 10);
    }

    /**
     */
    public function testBetweenFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([-1000]));
        $arg->between(-100, 100);
    }

    public function testMinPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->min(10);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([null]));
        $arg->min(10);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([50]));
        $arg->min(10);
        $this->validateArgument($arg, 50);

        $arg = new Argument($this->getMockedCommand([50]));
        $arg->min(50);
        $this->validateArgument($arg, 50);

        $arg = new Argument($this->getMockedCommand([50]));
        $arg->min(-10);
        $this->validateArgument($arg, 50);

        $arg = new Argument($this->getMockedCommand([-10]));
        $arg->min(-10);
        $this->validateArgument($arg, -10);
    }

    /**
     */
    public function testMinFailString()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->min(10);
    }

    /**
     */
    public function testMinFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([10.9]));
        $arg->min(11);
    }

    public function testMaxPass()
    {
        $arg = new Argument($this->getMockedCommand([]));
        $arg->max(100);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([null]));
        $arg->max(100);
        $this->validateArgument($arg, null);

        $arg = new Argument($this->getMockedCommand([50]));
        $arg->max(100);
        $this->validateArgument($arg, 50);

        $arg = new Argument($this->getMockedCommand([100]));
        $arg->max(100);
        $this->validateArgument($arg, 100);

        $arg = new Argument($this->getMockedCommand([-100]));
        $arg->max(-10);
        $this->validateArgument($arg, -100);

        $arg = new Argument($this->getMockedCommand([-10]));
        $arg->max(-10);
        $this->validateArgument($arg, -10);
    }

    /**
     */
    public function testMaxFailString()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand(['foo']));
        $arg->max(10);
    }

    /**
     */
    public function testMaxFail()
    {
        $this->setExpectedException(\Intervention\Image\Exception\InvalidArgumentException::class);

        $arg = new Argument($this->getMockedCommand([25]));
        $arg->max(10);
    }

    public function testValueDefault()
    {
        $arg = new Argument($this->getMockedCommand());
        $value = $arg->value('foo');
        $this->assertEquals('foo', $value);

        $arg = new Argument($this->getMockedCommand([null]));
        $value = $arg->value('foo');
        $this->assertEquals('foo', $value);
    }

    private function validateArgument($argument, $value)
    {
        $this->assertInstanceOf('\Intervention\Image\Commands\Argument', $argument);
        $this->assertEquals($value, $argument->value());
    }

    private function getMockedCommand($arguments = [])
    {
        return $this->getMockForAbstractClass('\Intervention\Image\Commands\AbstractCommand', [$arguments]);
    }
}
