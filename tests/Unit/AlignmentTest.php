<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Generator;
use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\InputDataProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;

class AlignmentTest extends BaseTestCase
{
    #[DataProviderExternal(InputDataProvider::class, 'alignmentInputs')]
    public function testCreate(string|Alignment $value, Alignment $result): void
    {
        $this->assertEquals($result, Alignment::create($value));
    }

    #[DataProviderExternal(InputDataProvider::class, 'alignmentInputs')]
    public function testTryCreate(string|Alignment $value, Alignment $result): void
    {
        $this->assertEquals($result, Alignment::tryCreate($value));
    }

    public function testCreateInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Alignment::create('invalid');
    }

    public function testTryCreateInvalid(): void
    {
        $this->assertNull(Alignment::tryCreate('invalid'));
    }

    #[DataProvider('alignHorizontallyDataProvider')]
    public function testAlignHorizontally(Alignment $base, string|Alignment $alignTo, Alignment $result): void
    {
        $this->assertEquals($result, $base->alignHorizontally($alignTo));
    }

    #[DataProvider('alignVerticallyDataProvider')]
    public function testAlignVertically(Alignment $base, string|Alignment $alignTo, Alignment $result): void
    {
        $this->assertEquals($result, $base->alignVertically($alignTo));
    }

    public function testAlignHorizontallyFallback(): void
    {
        $this->assertEquals(Alignment::CENTER, Alignment::CENTER->alignHorizontally('foo'));
    }

    public function testAlignVerticallyFallback(): void
    {
        $this->assertEquals(Alignment::CENTER, Alignment::CENTER->alignVertically('foo'));
    }

    public function testHorizontal(): void
    {
        $this->assertEquals(Alignment::LEFT, Alignment::TOP_LEFT->horizontal());
        $this->assertEquals(Alignment::LEFT, Alignment::LEFT->horizontal());
        $this->assertEquals(Alignment::LEFT, Alignment::BOTTOM_LEFT->horizontal());

        $this->assertEquals(Alignment::RIGHT, Alignment::TOP_RIGHT->horizontal());
        $this->assertEquals(Alignment::RIGHT, Alignment::RIGHT->horizontal());
        $this->assertEquals(Alignment::RIGHT, Alignment::BOTTOM_RIGHT->horizontal());

        $this->assertEquals(Alignment::CENTER, Alignment::TOP->horizontal());
        $this->assertEquals(Alignment::CENTER, Alignment::BOTTOM->horizontal());
        $this->assertEquals(Alignment::CENTER, Alignment::CENTER->horizontal());
    }

    public function testVertical(): void
    {
        $this->assertEquals(Alignment::TOP, Alignment::TOP_LEFT->vertical());
        $this->assertEquals(Alignment::TOP, Alignment::TOP->vertical());
        $this->assertEquals(Alignment::TOP, Alignment::TOP_RIGHT->vertical());

        $this->assertEquals(Alignment::BOTTOM, Alignment::BOTTOM_LEFT->vertical());
        $this->assertEquals(Alignment::BOTTOM, Alignment::BOTTOM->vertical());
        $this->assertEquals(Alignment::BOTTOM, Alignment::BOTTOM_RIGHT->vertical());

        $this->assertEquals(Alignment::CENTER, Alignment::LEFT->vertical());
        $this->assertEquals(Alignment::CENTER, Alignment::RIGHT->vertical());
        $this->assertEquals(Alignment::CENTER, Alignment::CENTER->vertical());
    }

    public static function alignHorizontallyDataProvider(): Generator
    {
        yield [Alignment::TOP_LEFT, Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::TOP, Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::TOP_RIGHT, Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::LEFT, Alignment::TOP_LEFT, Alignment::LEFT];
        yield [Alignment::CENTER, Alignment::TOP_LEFT, Alignment::LEFT];
        yield [Alignment::RIGHT, Alignment::TOP_LEFT, Alignment::LEFT];
        yield [Alignment::BOTTOM_LEFT, Alignment::TOP_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, Alignment::TOP_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM_RIGHT, Alignment::TOP_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP_LEFT, 'top-left', Alignment::TOP_LEFT];
        yield [Alignment::TOP, 'top-left', Alignment::TOP_LEFT];
        yield [Alignment::TOP_RIGHT, 'top-left', Alignment::TOP_LEFT];
        yield [Alignment::LEFT, 'top-left', Alignment::LEFT];
        yield [Alignment::CENTER, 'top-left', Alignment::LEFT];
        yield [Alignment::RIGHT, 'top-left', Alignment::LEFT];
        yield [Alignment::BOTTOM_LEFT, 'top-left', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, 'top-left', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM_RIGHT, 'top-left', Alignment::BOTTOM_LEFT];

        yield [Alignment::TOP_LEFT, Alignment::TOP, Alignment::TOP];
        yield [Alignment::TOP, Alignment::TOP, Alignment::TOP];
        yield [Alignment::TOP_RIGHT, Alignment::TOP, Alignment::TOP];
        yield [Alignment::LEFT, Alignment::TOP, Alignment::CENTER];
        yield [Alignment::CENTER, Alignment::TOP, Alignment::CENTER];
        yield [Alignment::RIGHT, Alignment::TOP, Alignment::CENTER];
        yield [Alignment::BOTTOM_LEFT, Alignment::TOP, Alignment::BOTTOM];
        yield [Alignment::BOTTOM, Alignment::TOP, Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, Alignment::TOP, Alignment::BOTTOM];
        yield [Alignment::TOP_LEFT, 'top', Alignment::TOP];
        yield [Alignment::TOP, 'top', Alignment::TOP];
        yield [Alignment::TOP_RIGHT, 'top', Alignment::TOP];
        yield [Alignment::LEFT, 'top', Alignment::CENTER];
        yield [Alignment::CENTER, 'top', Alignment::CENTER];
        yield [Alignment::RIGHT, 'top', Alignment::CENTER];
        yield [Alignment::BOTTOM_LEFT, 'top', Alignment::BOTTOM];
        yield [Alignment::BOTTOM, 'top', Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, 'top', Alignment::BOTTOM];

        yield [Alignment::TOP_LEFT, Alignment::TOP_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP, Alignment::TOP_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP_RIGHT, Alignment::TOP_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, Alignment::TOP_RIGHT, Alignment::RIGHT];
        yield [Alignment::CENTER, Alignment::TOP_RIGHT, Alignment::RIGHT];
        yield [Alignment::RIGHT, Alignment::TOP_RIGHT, Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::TOP_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM, Alignment::TOP_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_RIGHT, Alignment::TOP_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::TOP_LEFT, 'top-right', Alignment::TOP_RIGHT];
        yield [Alignment::TOP, 'top-right', Alignment::TOP_RIGHT];
        yield [Alignment::TOP_RIGHT, 'top-right', Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, 'top-right', Alignment::RIGHT];
        yield [Alignment::CENTER, 'top-right', Alignment::RIGHT];
        yield [Alignment::RIGHT, 'top-right', Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'top-right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM, 'top-right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_RIGHT, 'top-right', Alignment::BOTTOM_RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::LEFT, Alignment::TOP_LEFT];
        yield [Alignment::TOP, Alignment::LEFT, Alignment::TOP_LEFT];
        yield [Alignment::TOP_RIGHT, Alignment::LEFT, Alignment::TOP_LEFT];
        yield [Alignment::LEFT, Alignment::LEFT, Alignment::LEFT];
        yield [Alignment::CENTER, Alignment::LEFT, Alignment::LEFT];
        yield [Alignment::RIGHT, Alignment::LEFT, Alignment::LEFT];
        yield [Alignment::BOTTOM_LEFT, Alignment::LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, Alignment::LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM_RIGHT, Alignment::LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP_LEFT, 'left', Alignment::TOP_LEFT];
        yield [Alignment::TOP, 'left', Alignment::TOP_LEFT];
        yield [Alignment::TOP_RIGHT, 'left', Alignment::TOP_LEFT];
        yield [Alignment::LEFT, 'left', Alignment::LEFT];
        yield [Alignment::CENTER, 'left', Alignment::LEFT];
        yield [Alignment::RIGHT, 'left', Alignment::LEFT];
        yield [Alignment::BOTTOM_LEFT, 'left', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, 'left', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM_RIGHT, 'left', Alignment::BOTTOM_LEFT];

        yield [Alignment::TOP_LEFT, Alignment::CENTER, Alignment::TOP];
        yield [Alignment::TOP, Alignment::CENTER, Alignment::TOP];
        yield [Alignment::TOP_RIGHT, Alignment::CENTER, Alignment::TOP];
        yield [Alignment::LEFT, Alignment::CENTER, Alignment::CENTER];
        yield [Alignment::CENTER, Alignment::CENTER, Alignment::CENTER];
        yield [Alignment::RIGHT, Alignment::CENTER, Alignment::CENTER];
        yield [Alignment::BOTTOM_LEFT, Alignment::CENTER, Alignment::BOTTOM];
        yield [Alignment::BOTTOM, Alignment::CENTER, Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, Alignment::CENTER, Alignment::BOTTOM];
        yield [Alignment::TOP_LEFT, 'center', Alignment::TOP];
        yield [Alignment::TOP, 'center', Alignment::TOP];
        yield [Alignment::TOP_RIGHT, 'center', Alignment::TOP];
        yield [Alignment::LEFT, 'center', Alignment::CENTER];
        yield [Alignment::CENTER, 'center', Alignment::CENTER];
        yield [Alignment::RIGHT, 'center', Alignment::CENTER];
        yield [Alignment::BOTTOM_LEFT, 'center', Alignment::BOTTOM];
        yield [Alignment::BOTTOM, 'center', Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, 'center', Alignment::BOTTOM];

        yield [Alignment::TOP_LEFT, Alignment::RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP, Alignment::RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP_RIGHT, Alignment::RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, Alignment::RIGHT, Alignment::RIGHT];
        yield [Alignment::CENTER, Alignment::RIGHT, Alignment::RIGHT];
        yield [Alignment::RIGHT, Alignment::RIGHT, Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM, Alignment::RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_RIGHT, Alignment::RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::TOP_LEFT, 'right', Alignment::TOP_RIGHT];
        yield [Alignment::TOP, 'right', Alignment::TOP_RIGHT];
        yield [Alignment::TOP_RIGHT, 'right', Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, 'right', Alignment::RIGHT];
        yield [Alignment::CENTER, 'right', Alignment::RIGHT];
        yield [Alignment::RIGHT, 'right', Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM, 'right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_RIGHT, 'right', Alignment::BOTTOM_RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::BOTTOM_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::TOP, Alignment::BOTTOM_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::TOP_RIGHT, Alignment::BOTTOM_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::LEFT, Alignment::BOTTOM_LEFT, Alignment::LEFT];
        yield [Alignment::CENTER, Alignment::BOTTOM_LEFT, Alignment::LEFT];
        yield [Alignment::RIGHT, Alignment::BOTTOM_LEFT, Alignment::LEFT];
        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP_LEFT, 'bottom-left', Alignment::TOP_LEFT];
        yield [Alignment::TOP, 'bottom-left', Alignment::TOP_LEFT];
        yield [Alignment::TOP_RIGHT, 'bottom-left', Alignment::TOP_LEFT];
        yield [Alignment::LEFT, 'bottom-left', Alignment::LEFT];
        yield [Alignment::CENTER, 'bottom-left', Alignment::LEFT];
        yield [Alignment::RIGHT, 'bottom-left', Alignment::LEFT];
        yield [Alignment::BOTTOM_LEFT, 'bottom-left', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, 'bottom-left', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM_RIGHT, 'bottom-left', Alignment::BOTTOM_LEFT];

        yield [Alignment::TOP_LEFT, Alignment::BOTTOM, Alignment::TOP];
        yield [Alignment::TOP, Alignment::BOTTOM, Alignment::TOP];
        yield [Alignment::TOP_RIGHT, Alignment::BOTTOM, Alignment::TOP];
        yield [Alignment::LEFT, Alignment::BOTTOM, Alignment::CENTER];
        yield [Alignment::CENTER, Alignment::BOTTOM, Alignment::CENTER];
        yield [Alignment::RIGHT, Alignment::BOTTOM, Alignment::CENTER];
        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM, Alignment::BOTTOM];
        yield [Alignment::BOTTOM, Alignment::BOTTOM, Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM, Alignment::BOTTOM];
        yield [Alignment::TOP_LEFT, 'bottom', Alignment::TOP];
        yield [Alignment::TOP, 'bottom', Alignment::TOP];
        yield [Alignment::TOP_RIGHT, 'bottom', Alignment::TOP];
        yield [Alignment::LEFT, 'bottom', Alignment::CENTER];
        yield [Alignment::CENTER, 'bottom', Alignment::CENTER];
        yield [Alignment::RIGHT, 'bottom', Alignment::CENTER];
        yield [Alignment::BOTTOM_LEFT, 'bottom', Alignment::BOTTOM];
        yield [Alignment::BOTTOM, 'bottom', Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, 'bottom', Alignment::BOTTOM];

        yield [Alignment::TOP_LEFT, Alignment::BOTTOM_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP, Alignment::BOTTOM_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP_RIGHT, Alignment::BOTTOM_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, Alignment::BOTTOM_RIGHT, Alignment::RIGHT];
        yield [Alignment::CENTER, Alignment::BOTTOM_RIGHT, Alignment::RIGHT];
        yield [Alignment::RIGHT, Alignment::BOTTOM_RIGHT, Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::TOP_LEFT, 'bottom-right', Alignment::TOP_RIGHT];
        yield [Alignment::TOP, 'bottom-right', Alignment::TOP_RIGHT];
        yield [Alignment::TOP_RIGHT, 'bottom-right', Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, 'bottom-right', Alignment::RIGHT];
        yield [Alignment::CENTER, 'bottom-right', Alignment::RIGHT];
        yield [Alignment::RIGHT, 'bottom-right', Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'bottom-right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM, 'bottom-right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_RIGHT, 'bottom-right', Alignment::BOTTOM_RIGHT];
    }

    public static function alignVerticallyDataProvider(): Generator
    {
        yield [Alignment::TOP_LEFT, Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::TOP, Alignment::TOP_LEFT, Alignment::TOP];
        yield [Alignment::TOP_RIGHT, Alignment::TOP_LEFT, Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::CENTER, Alignment::TOP_LEFT, Alignment::TOP];
        yield [Alignment::RIGHT, Alignment::TOP_LEFT, Alignment::TOP_RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::TOP_LEFT, Alignment::TOP_LEFT];
        yield [Alignment::BOTTOM, Alignment::TOP_LEFT, Alignment::TOP];
        yield [Alignment::BOTTOM_RIGHT, Alignment::TOP_LEFT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP_LEFT, 'top-left', Alignment::TOP_LEFT];
        yield [Alignment::TOP, 'top-left', Alignment::TOP];
        yield [Alignment::TOP_RIGHT, 'top-left', Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, 'top-left', Alignment::TOP_LEFT];
        yield [Alignment::CENTER, 'top-left', Alignment::TOP];
        yield [Alignment::RIGHT, 'top-left', Alignment::TOP_RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'top-left', Alignment::TOP_LEFT];
        yield [Alignment::BOTTOM, 'top-left', Alignment::TOP];
        yield [Alignment::BOTTOM_RIGHT, 'top-left', Alignment::TOP_RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::TOP, Alignment::TOP_LEFT];
        yield [Alignment::TOP, Alignment::TOP, Alignment::TOP];
        yield [Alignment::TOP_RIGHT, Alignment::TOP, Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, Alignment::TOP, Alignment::TOP_LEFT];
        yield [Alignment::CENTER, Alignment::TOP, Alignment::TOP];
        yield [Alignment::RIGHT, Alignment::TOP, Alignment::TOP_RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::TOP, Alignment::TOP_LEFT];
        yield [Alignment::BOTTOM, Alignment::TOP, Alignment::TOP];
        yield [Alignment::BOTTOM_RIGHT, Alignment::TOP, Alignment::TOP_RIGHT];
        yield [Alignment::TOP_LEFT, 'top', Alignment::TOP_LEFT];
        yield [Alignment::TOP, 'top', Alignment::TOP];
        yield [Alignment::TOP_RIGHT, 'top', Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, 'top', Alignment::TOP_LEFT];
        yield [Alignment::CENTER, 'top', Alignment::TOP];
        yield [Alignment::RIGHT, 'top', Alignment::TOP_RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'top', Alignment::TOP_LEFT];
        yield [Alignment::BOTTOM, 'top', Alignment::TOP];
        yield [Alignment::BOTTOM_RIGHT, 'top', Alignment::TOP_RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::TOP_RIGHT, Alignment::TOP_LEFT];
        yield [Alignment::TOP, Alignment::TOP_RIGHT, Alignment::TOP];
        yield [Alignment::TOP_RIGHT, Alignment::TOP_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, Alignment::TOP_RIGHT, Alignment::TOP_LEFT];
        yield [Alignment::CENTER, Alignment::TOP_RIGHT, Alignment::TOP];
        yield [Alignment::RIGHT, Alignment::TOP_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::TOP_RIGHT, Alignment::TOP_LEFT];
        yield [Alignment::BOTTOM, Alignment::TOP_RIGHT, Alignment::TOP];
        yield [Alignment::BOTTOM_RIGHT, Alignment::TOP_RIGHT, Alignment::TOP_RIGHT];
        yield [Alignment::TOP_LEFT, 'top-right', Alignment::TOP_LEFT];
        yield [Alignment::TOP, 'top-right', Alignment::TOP];
        yield [Alignment::TOP_RIGHT, 'top-right', Alignment::TOP_RIGHT];
        yield [Alignment::LEFT, 'top-right', Alignment::TOP_LEFT];
        yield [Alignment::CENTER, 'top-right', Alignment::TOP];
        yield [Alignment::RIGHT, 'top-right', Alignment::TOP_RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'top-right', Alignment::TOP_LEFT];
        yield [Alignment::BOTTOM, 'top-right', Alignment::TOP];
        yield [Alignment::BOTTOM_RIGHT, 'top-right', Alignment::TOP_RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::LEFT, Alignment::LEFT];
        yield [Alignment::TOP, Alignment::LEFT, Alignment::CENTER];
        yield [Alignment::TOP_RIGHT, Alignment::LEFT, Alignment::RIGHT];
        yield [Alignment::LEFT, Alignment::LEFT, Alignment::LEFT];
        yield [Alignment::CENTER, Alignment::LEFT, Alignment::CENTER];
        yield [Alignment::RIGHT, Alignment::LEFT, Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::LEFT, Alignment::LEFT];
        yield [Alignment::BOTTOM, Alignment::LEFT, Alignment::CENTER];
        yield [Alignment::BOTTOM_RIGHT, Alignment::LEFT, Alignment::RIGHT];
        yield [Alignment::TOP_LEFT, 'left', Alignment::LEFT];
        yield [Alignment::TOP, 'left', Alignment::CENTER];
        yield [Alignment::TOP_RIGHT, 'left', Alignment::RIGHT];
        yield [Alignment::LEFT, 'left', Alignment::LEFT];
        yield [Alignment::CENTER, 'left', Alignment::CENTER];
        yield [Alignment::RIGHT, 'left', Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'left', Alignment::LEFT];
        yield [Alignment::BOTTOM, 'left', Alignment::CENTER];
        yield [Alignment::BOTTOM_RIGHT, 'left', Alignment::RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::CENTER, Alignment::LEFT];
        yield [Alignment::TOP, Alignment::CENTER, Alignment::CENTER];
        yield [Alignment::TOP_RIGHT, Alignment::CENTER, Alignment::RIGHT];
        yield [Alignment::LEFT, Alignment::CENTER, Alignment::LEFT];
        yield [Alignment::CENTER, Alignment::CENTER, Alignment::CENTER];
        yield [Alignment::RIGHT, Alignment::CENTER, Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::CENTER, Alignment::LEFT];
        yield [Alignment::BOTTOM, Alignment::CENTER, Alignment::CENTER];
        yield [Alignment::BOTTOM_RIGHT, Alignment::CENTER, Alignment::RIGHT];
        yield [Alignment::TOP_LEFT, 'center', Alignment::LEFT];
        yield [Alignment::TOP, 'center', Alignment::CENTER];
        yield [Alignment::TOP_RIGHT, 'center', Alignment::RIGHT];
        yield [Alignment::LEFT, 'center', Alignment::LEFT];
        yield [Alignment::CENTER, 'center', Alignment::CENTER];
        yield [Alignment::RIGHT, 'center', Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'center', Alignment::LEFT];
        yield [Alignment::BOTTOM, 'center', Alignment::CENTER];
        yield [Alignment::BOTTOM_RIGHT, 'center', Alignment::RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::RIGHT, Alignment::LEFT];
        yield [Alignment::TOP, Alignment::RIGHT, Alignment::CENTER];
        yield [Alignment::TOP_RIGHT, Alignment::RIGHT, Alignment::RIGHT];
        yield [Alignment::LEFT, Alignment::RIGHT, Alignment::LEFT];
        yield [Alignment::CENTER, Alignment::RIGHT, Alignment::CENTER];
        yield [Alignment::RIGHT, Alignment::RIGHT, Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::RIGHT, Alignment::LEFT];
        yield [Alignment::BOTTOM, Alignment::RIGHT, Alignment::CENTER];
        yield [Alignment::BOTTOM_RIGHT, Alignment::RIGHT, Alignment::RIGHT];
        yield [Alignment::TOP_LEFT, 'right', Alignment::LEFT];
        yield [Alignment::TOP, 'right', Alignment::CENTER];
        yield [Alignment::TOP_RIGHT, 'right', Alignment::RIGHT];
        yield [Alignment::LEFT, 'right', Alignment::LEFT];
        yield [Alignment::CENTER, 'right', Alignment::CENTER];
        yield [Alignment::RIGHT, 'right', Alignment::RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'right', Alignment::LEFT];
        yield [Alignment::BOTTOM, 'right', Alignment::CENTER];
        yield [Alignment::BOTTOM_RIGHT, 'right', Alignment::RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP, Alignment::BOTTOM_LEFT, Alignment::BOTTOM];
        yield [Alignment::TOP_RIGHT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::LEFT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::CENTER, Alignment::BOTTOM_LEFT, Alignment::BOTTOM];
        yield [Alignment::RIGHT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, Alignment::BOTTOM_LEFT, Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_LEFT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::TOP_LEFT, 'bottom-left', Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP, 'bottom-left', Alignment::BOTTOM];
        yield [Alignment::TOP_RIGHT, 'bottom-left', Alignment::BOTTOM_RIGHT];
        yield [Alignment::LEFT, 'bottom-left', Alignment::BOTTOM_LEFT];
        yield [Alignment::CENTER, 'bottom-left', Alignment::BOTTOM];
        yield [Alignment::RIGHT, 'bottom-left', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'bottom-left', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, 'bottom-left', Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, 'bottom-left', Alignment::BOTTOM_RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::BOTTOM, Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP, Alignment::BOTTOM, Alignment::BOTTOM];
        yield [Alignment::TOP_RIGHT, Alignment::BOTTOM, Alignment::BOTTOM_RIGHT];
        yield [Alignment::LEFT, Alignment::BOTTOM, Alignment::BOTTOM_LEFT];
        yield [Alignment::CENTER, Alignment::BOTTOM, Alignment::BOTTOM];
        yield [Alignment::RIGHT, Alignment::BOTTOM, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, Alignment::BOTTOM, Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM, Alignment::BOTTOM_RIGHT];
        yield [Alignment::TOP_LEFT, 'bottom', Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP, 'bottom', Alignment::BOTTOM];
        yield [Alignment::TOP_RIGHT, 'bottom', Alignment::BOTTOM_RIGHT];
        yield [Alignment::LEFT, 'bottom', Alignment::BOTTOM_LEFT];
        yield [Alignment::CENTER, 'bottom', Alignment::BOTTOM];
        yield [Alignment::RIGHT, 'bottom', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'bottom', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, 'bottom', Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, 'bottom', Alignment::BOTTOM_RIGHT];

        yield [Alignment::TOP_LEFT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM];
        yield [Alignment::TOP_RIGHT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::LEFT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_LEFT];
        yield [Alignment::CENTER, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM];
        yield [Alignment::RIGHT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_LEFT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT, Alignment::BOTTOM_RIGHT];
        yield [Alignment::TOP_LEFT, 'bottom-right', Alignment::BOTTOM_LEFT];
        yield [Alignment::TOP, 'bottom-right', Alignment::BOTTOM];
        yield [Alignment::TOP_RIGHT, 'bottom-right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::LEFT, 'bottom-right', Alignment::BOTTOM_LEFT];
        yield [Alignment::CENTER, 'bottom-right', Alignment::BOTTOM];
        yield [Alignment::RIGHT, 'bottom-right', Alignment::BOTTOM_RIGHT];
        yield [Alignment::BOTTOM_LEFT, 'bottom-right', Alignment::BOTTOM_LEFT];
        yield [Alignment::BOTTOM, 'bottom-right', Alignment::BOTTOM];
        yield [Alignment::BOTTOM_RIGHT, 'bottom-right', Alignment::BOTTOM_RIGHT];
    }
}
