<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Decoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Colors\Rgb\Decoders\StringColorDecoder::class)]
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
            'rgb(204, 204, 204)',
            Color::class,
            [204, 204, 204, 255],
        ];
        yield [
            'rgb(204,204,204)',
            Color::class,
            [204, 204, 204, 255],
        ];
        yield [
            'rgb(100%,20%,0%)',
            Color::class,
            [255, 51, 0, 255],
        ];
        yield [
            'rgb(100%,19.8064%,0.1239483%)',
            Color::class,
            [255, 51, 0, 255],
        ];
        yield [
            'rgba(204, 204, 204, 1)',
            Color::class,
            [204, 204, 204, 255],
        ];
        yield [
            'rgba(204,204,204,.2)',
            Color::class,
            [204, 204, 204, 51],
        ];
        yield [
            'rgba(204,204,204,0.2)',
            Color::class,
            [204, 204, 204, 51],
        ];
        yield [
            'srgb(255, 0, 0)',
            Color::class,
            [255, 0, 0, 255],
        ];
    }
}
