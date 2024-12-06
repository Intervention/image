<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(Cloner::class)]
final class ClonerTest extends BaseTestCase
{
    public function testClone(): void
    {
        $gd = imagecreatefromgif($this->getTestResourcePath('gradient.gif'));
        $clone = Cloner::clone($gd);

        $this->assertEquals(16, imagesx($gd));
        $this->assertEquals(16, imagesy($gd));
        $this->assertEquals(16, imagesx($clone));
        $this->assertEquals(16, imagesy($clone));

        $this->assertEquals(
            imagecolorsforindex($gd, imagecolorat($gd, 10, 10)),
            imagecolorsforindex($clone, imagecolorat($clone, 10, 10))
        );
    }

    public function testCloneEmpty(): void
    {
        $gd = imagecreatefromgif($this->getTestResourcePath('gradient.gif'));
        $clone = Cloner::cloneEmpty($gd, new Rectangle(12, 12), new Color(255, 0, 0, 0));

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
            imagecolorsforindex($clone, imagecolorat($clone, 10, 10))
        );
    }

    public function testCloneBlended(): void
    {
        $gd = imagecreatefromgif($this->getTestResourcePath('gradient.gif'));
        $clone = Cloner::cloneBlended($gd, new Color(255, 0, 255, 255));

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
            imagecolorsforindex($clone, imagecolorat($clone, 1, 0))
        );
    }
}
