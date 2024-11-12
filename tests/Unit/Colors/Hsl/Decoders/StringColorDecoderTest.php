<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsl\Decoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Hsl\Color;
use Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder::class)]
final class StringColorDecoderTest extends BaseTestCase
{
    #[DataProvider('decodeDataProvier')]
    public function testDecode(string $input, string $classname, array $channelValues): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode($input);
        $this->assertInstanceOf($classname, $result);
        $this->assertEquals($channelValues, $result->toArray());
    }

    public static function decodeDataProvier(): Generator
    {
        yield [
            'hsl(0,0,0)',
            Color::class,
            [0, 0, 0],
        ];
        yield [
            'hsl(0, 100, 50)',
            Color::class,
            [0, 100, 50],
        ];
        yield [
            'hsl(360, 100, 50)',
            Color::class,
            [360, 100, 50],
        ];
        yield [
            'hsl(180, 100%, 50%)',
            Color::class,
            [180, 100, 50],
        ];
    }
}
