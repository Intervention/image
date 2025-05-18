<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Typography;

use Generator;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\UnicodeBlock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(UnicodeBlock::class)]
class UnicodeBlockTest extends BaseTestCase
{
    #[DataProvider('fromStringDataProvider')]
    public function testFromString(string $input, UnicodeBlock $result): void
    {
        $this->assertEquals(UnicodeBlock::fromString($input), $result);
    }

    public static function fromStringDataProvider(): Generator
    {
        yield ["just a test", UnicodeBlock::LATIN];
        yield ["这是一次测试", UnicodeBlock::CHINESE];
        yield ["이것은 테스트입니다.", UnicodeBlock::KOREAN];
        yield ["هذا اختبار", UnicodeBlock::ARABIC];
        yield ["これはテストである。", UnicodeBlock::JAPANESE];
        yield ["นี่คือการทดสอบ", UnicodeBlock::THAI];
    }
}
