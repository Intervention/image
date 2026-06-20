<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Traits;

use Generator;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Traits\CanCreateRandomString;
use PHPUnit\Framework\Attributes\DataProvider;

final class CanCreateRandomStringTest extends BaseTestCase
{
    use CanCreateRandomString;

    public function testRandomStringDefault(): void
    {
        $random = self::randomString();
        $this->assertIsString($random);
        $this->assertEquals(32, strlen($random));
    }

    #[DataProvider('provideLength')]
    public function testRandomStringLength(int $length): void
    {
        $random = self::randomString($length);
        $this->assertIsString($random);
        $this->assertEquals($length, strlen($random));
    }

    public static function provideLength(): Generator
    {
        yield [1];
        yield [2];
        yield [3];
        yield [4];
        yield [5];
        yield [6];
        yield [12];
        yield [24];
        yield [48];
        yield [96];
        yield [128];
        yield [256];
    }

    public function testRandomStringLengthNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        self::randomString(0);
    }

    public function testRandomStringLengthNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        self::randomString(-32);
    }

    public function testRandomStringLengthTooLarge(): void
    {
        $this->expectException(InvalidArgumentException::class);
        self::randomString(320);
    }
}
