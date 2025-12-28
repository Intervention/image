<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(HexColorDecoder::class)]
final class HexColorDecoderTest extends BaseTestCase
{
    /**
     * @param $channelValues array<int>
     */
    #[DataProvider('decodeDataProvier')]
    public function testDecode(string $input, string $classname, array $channelValues): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode($input);
        $this->assertInstanceOf($classname, $result);
        $this->assertEquals($channelValues, $result->toArray());
    }

    public static function decodeDataProvier(): Generator
    {
        yield [
            'ccc',
            Color::class,
            [204, 204, 204, 1]
        ];
        yield [
            'ccff33',
            Color::class,
            [204, 255, 51, 1],
        ];
        yield [
            '#ccc',
            Color::class,
            [204, 204, 204, 1],
        ];
        yield [
            'cccccc',
            Color::class,
            [204, 204, 204, 1],
        ];
        yield [
            '#cccccc',
            Color::class,
            [204, 204, 204, 1],
        ];
        yield [
            '#ccccccff',
            Color::class,
            [204, 204, 204, 1],
        ];
        yield [
            '#cccf',
            Color::class,
            [204, 204, 204, 1],
        ];
        yield [
            'ccccccff',
            Color::class,
            [204, 204, 204, 1],
        ];
        yield [
            'cccf',
            Color::class,
            [204, 204, 204, 1],
        ];
        yield [
            '#b53717aa',
            Color::class,
            [181, 55, 23, 0.6666666666666666],
        ];
    }
}
