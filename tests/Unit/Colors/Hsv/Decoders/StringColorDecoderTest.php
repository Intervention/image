<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsv\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Hsv\Color;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder::class)]
final class StringColorDecoderTest extends BaseTestCase
{
    #[DataProvider('decodeDataProvier')]
    public function testDecodeHsv(string $input, string $classname, array $channelValues): void
    {
        $decoder = new StringColorDecoder();
        $result = $decoder->decode($input);
        $this->assertInstanceOf($classname, $result);
        $this->assertEquals($channelValues, $result->toArray());
    }

    public static function decodeDataProvier(): array
    {
        return [
            [
                'hsv(0,0,0)',
                Color::class,
                [0, 0, 0],
            ],
            [
                'hsv(0, 100, 100)',
                Color::class,
                [0, 100, 100],
            ],
            [
                'hsv(360, 100, 100)',
                Color::class,
                [360, 100, 100],
            ],
            [
                'hsv(180, 100%, 100%)',
                Color::class,
                [180, 100, 100],
            ],
            [
                'hsb(0,0,0)',
                Color::class,
                [0, 0, 0],
            ],
            [
                'hsb(0, 100, 100)',
                Color::class,
                [0, 100, 100],
            ],
            [
                'hsb(360, 100, 100)',
                Color::class,
                [360, 100, 100],
            ],
            [
                'hsb(180, 100%, 100%)',
                Color::class,
                [180, 100, 100],
            ],
        ];
    }
}
