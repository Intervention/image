<?php

namespace Intervention\Image\Tests\Drivers\Abstract;

use Intervention\Image\Drivers\Abstract\AbstractFont;
use Intervention\Image\Interfaces\ColorInterface;
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

        // settings
        $mock->size(24);
        $mock->angle(30);
        $mock->filename(__DIR__ . '/AbstractFontTest.php');
        $mock->color('ccc');
        $mock->align('center');
        $mock->valign('top');

        $mock->shouldReceive('handleInput')->andReturn(
            Mockery::mock(ColorInterface::class)
        );

        return $mock;
    }

    public function testConstructor(): void
    {
        $mock = $this->getAbstractFontMock();
        $this->assertEquals(24.0, $mock->getSize());
        $this->assertEquals(30, $mock->getAngle());
        $this->assertEquals(__DIR__ . '/AbstractFontTest.php', $mock->getFilename());
        $this->assertInstanceOf(ColorInterface::class, $mock->getColor());
        $this->assertEquals('center', $mock->getAlign());
        $this->assertEquals('top', $mock->getValign());
        $this->assertTrue($mock->hasFilename());
    }
}
