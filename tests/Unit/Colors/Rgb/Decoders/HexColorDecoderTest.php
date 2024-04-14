<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder::class)]
final class HexColorDecoderTest extends BaseTestCase
{
    #[DataProvider('decodeDataProvier')]
    public function testDecode(string $input, string $classname, array $channelValues): void
    {
        $decoder = new HexColorDecoder();
        $result = $decoder->decode($input);
        $this->assertInstanceOf($classname, $result);
        $this->assertEquals($channelValues, $result->toArray());
    }

    public static function decodeDataProvier(): array
    {
        return [
            [
                'ccc',
                Color::class,
                [204, 204, 204, 255]
            ],
            [
                'ccff33',
                Color::class,
                [204, 255, 51, 255],
            ],
            [
                '#ccc',
                Color::class,
                [204, 204, 204, 255],
            ],
            [
                'cccccc',
                Color::class,
                [204, 204, 204, 255],
            ],
            [
                '#cccccc',
                Color::class,
                [204, 204, 204, 255],
            ],
            [
                '#ccccccff',
                Color::class,
                [204, 204, 204, 255],
            ],
            [
                '#cccf',
                Color::class,
                [204, 204, 204, 255],
            ],
            [
                'ccccccff',
                Color::class,
                [204, 204, 204, 255],
            ],
            [
                'cccf',
                Color::class,
                [204, 204, 204, 255],
            ],
            [
                '#b53717aa',
                Color::class,
                [181, 55, 23, 170],
            ],
        ];
    }
}
