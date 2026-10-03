<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd;

use Generator;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(Cloner::class)]
final class ClonerTest extends BaseTestCase
{
    public function testClone(): void
    {
        $gd = imagecreatefromgif(Resource::create('gradient.gif')->path());
        $clone = Cloner::clone($gd);

        $this->assertEquals(16, imagesx($gd));
        $this->assertEquals(16, imagesy($gd));
        $this->assertEquals(16, imagesx($clone));
        $this->assertEquals(16, imagesy($clone));

        $this->assertEquals(
            imagecolorsforindex($gd, imagecolorat($gd, 10, 10)),
            imagecolorsforindex($clone, imagecolorat($clone, 10, 10)),
        );
    }

    public function testCloneEmpty(): void
    {
        $gd = imagecreatefromgif(Resource::create('gradient.gif')->path());
        $clone = Cloner::cloneEmpty($gd, new Size(12, 12), new Color(255, 0, 0, 0));

        $this->assertEquals(16, imagesx($gd));
        $this->assertEquals(16, imagesy($gd));
        $this->assertEquals(12, imagesx($clone));
        $this->assertEquals(12, imagesy($clone));

        $this->assertEquals(
            ['red' => 0, 'green' => 255, 'blue' => 2, 'alpha' => 0],
            imagecolorsforindex($gd, imagecolorat($gd, 10, 10)),
        );

        $this->assertEquals(
            ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 127],
            imagecolorsforindex($clone, imagecolorat($clone, 10, 10)),
        );
    }

    public function testCloneBlended(): void
    {
        $gd = imagecreatefromgif(Resource::create('gradient.gif')->path());
        $clone = Cloner::cloneBlended($gd, new Color(255, 0, 255, 1));

        $this->assertEquals(16, imagesx($gd));
        $this->assertEquals(16, imagesy($gd));
        $this->assertEquals(16, imagesx($clone));
        $this->assertEquals(16, imagesy($clone));

        $this->assertEquals(
            ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 127],
            imagecolorsforindex($gd, imagecolorat($gd, 1, 0)),
        );

        $this->assertEquals(
            ['red' => 255, 'green' => 0, 'blue' => 255, 'alpha' => 0],
            imagecolorsforindex($clone, imagecolorat($clone, 1, 0)),
        );
    }

    #[DataProvider('cloneEmptyTransparentColorDataProvider')]
    public function testCloneEmptyTransparentColor(Color $background, int $transparent): void
    {
        $clone = Cloner::cloneEmpty(imagecreatetruecolor(3, 2), background: $background);
        $this->assertEquals($transparent, imagecolortransparent($clone));
    }

    public static function cloneEmptyTransparentColorDataProvider(): Generator
    {
        yield [new Color(255, 0, 0, 0), 0x7FFF0000];
        yield [new Color(255, 0, 0, .25), 0x5FFF0000];
        yield [new Color(255, 0, 0, .5), -1];
        yield [new Color(255, 0, 0, .75), -1];
        yield [new Color(255, 0, 0, 1), -1];
    }

    #[DataProvider('cloneKeepsSemiTransparentBackgroundDataProvider')]
    public function testCloneKeepsSemiTransparentBackground(Color $background, int $gdAlpha): void
    {
        $gd = Cloner::cloneEmpty(imagecreatetruecolor(3, 3), background: $background);
        $clone = Cloner::clone($gd);

        $this->assertEquals(
            ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => $gdAlpha],
            imagecolorsforindex($clone, imagecolorat($clone, 1, 1)),
        );
    }

    public static function cloneKeepsSemiTransparentBackgroundDataProvider(): Generator
    {
        yield [new Color(255, 0, 0, 0), 127];
        yield [new Color(255, 0, 0, .25), 95];
        yield [new Color(255, 0, 0, .5), 63];
        yield [new Color(255, 0, 0, .75), 32];
        yield [new Color(255, 0, 0, 1), 0];
    }
}
