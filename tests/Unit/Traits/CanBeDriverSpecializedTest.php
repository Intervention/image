<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Traits;

use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Traits\CanBeDriverSpecialized;
use Mockery;

final class CanBeDriverSpecializedTest extends BaseTestCase
{
    public function testSpecializable(): void
    {
        $object = new class (10, 'foo') implements SpecializableInterface {
            use CanBeDriverSpecialized;

            public function __construct(
                public int $amount,
                public string $name,
            ) {
            }
        };

        $result = $object->specializable();
        $this->assertIsArray($result);
        $this->assertEquals(['amount' => 10, 'name' => 'foo'], $result);
    }

    public function testSpecializableWithoutConstructor(): void
    {
        $object = new class () implements SpecializableInterface {
            use CanBeDriverSpecialized;
        };

        $result = $object->specializable();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testDriverThrowsWhenNotSet(): void
    {
        $object = new class () implements SpecializableInterface {
            use CanBeDriverSpecialized;
        };

        $this->expectException(StateException::class);
        $this->expectExceptionMessage('Use setDriver()');
        $object->driver();
    }

    public function testSetDriverAndDriver(): void
    {
        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('id')->andReturn('Test');

        $object = new class () implements SpecializableInterface {
            use CanBeDriverSpecialized;

            /**
             * Override belongsToDriver to always return true for testing.
             */
            protected function belongsToDriver(object $driver): bool
            {
                return true;
            }
        };

        $result = $object->setDriver($driver);
        $this->assertSame($object, $result);
        $this->assertSame($driver, $object->driver());
    }

    public function testSetDriverFailsWhenNotBelonging(): void
    {
        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('id')->andReturn('Test');

        $object = new class () implements SpecializableInterface {
            use CanBeDriverSpecialized;

            /**
             * Override belongsToDriver to always return false for testing.
             */
            protected function belongsToDriver(object $driver): bool
            {
                return false;
            }
        };

        $this->expectException(NotSupportedException::class);
        $object->setDriver($driver);
    }

    public function testBelongsToDriver(): void
    {
        // Use a real driver-namespaced modifier to test belongsToDriver
        $modifier = new \Intervention\Image\Drivers\Gd\Modifiers\BlurModifier(5);
        $driver = new \Intervention\Image\Drivers\Gd\Driver();

        // setDriver should succeed because modifier and driver share namespace
        $result = $modifier->setDriver($driver);
        $this->assertSame($modifier, $result);
    }

    public function testBelongsToDriverFails(): void
    {
        // Use a Gd-namespaced modifier with an Imagick driver.
        // The modifier's belongsToDriver uses str_starts_with on namespaces,
        // so a Gd modifier should not belong to an Imagick driver.
        $modifier = new \Intervention\Image\Drivers\Gd\Modifiers\BlurModifier(5);
        $driver = new \Intervention\Image\Drivers\Imagick\Driver();

        $this->expectException(NotSupportedException::class);
        $modifier->setDriver($driver);
    }
}
