<?php

namespace Intervention\Image\Tests\Drivers\Abstract;

use Intervention\Image\Drivers\AbstractFont;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\TestCase;
use Mockery;

class AbstractFontTest extends TestCase
{
    private function getAbstractFontMock()
    {
        // create mock
        $mock = Mockery::mock(AbstractFont::class)
                ->shouldAllowMockingProtectedMethods()
                ->makePartial();

        $mock->shouldReceive('getBoxSize')->with('Hy')->andReturn(new Rectangle(123, 456));
        $mock->shouldReceive('getBoxSize')->with('T')->andReturn(new Rectangle(12, 34));

        // settings
        $mock->setSize(24);
        $mock->setAngle(30);
        $mock->setFilename(__DIR__ . '/AbstractFontTest.php');
        $mock->setColor('ccc');
        $mock->setAlignment('center');
        $mock->setValignment('top');
        $mock->setLineHeight(1.5);

        return $mock;
    }

    public function testConstructor(): void
    {
        $mock = $this->getAbstractFontMock();
        $this->assertEquals(24.0, $mock->size());
        $this->assertEquals(30, $mock->angle());
        $this->assertEquals(__DIR__ . '/AbstractFontTest.php', $mock->filename());
        $this->assertEquals('ccc', $mock->color());
        $this->assertEquals('center', $mock->alignment());
        $this->assertEquals('top', $mock->valignment());
        $this->assertEquals(1.5, $mock->lineHeight());
        $this->assertEquals(456, $mock->fontSizeInPixels());
        $this->assertEquals(34, $mock->capHeight());
        $this->assertEquals(684, $mock->leadingInPixels());
        $this->assertTrue($mock->hasFilename());
    }
}
