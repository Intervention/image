<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Fraction;
use Intervention\Image\Tests\BaseTestCase;

class FractionTest extends BaseTestCase
{
    public function testCalculation(): void
    {
        $this->assertEquals(12, Fraction::FULL->of(12));
        $this->assertEquals(24, Fraction::DOUBLE->of(12));
        $this->assertEquals(18, Fraction::ONE_AND_A_HALF->of(12));
        $this->assertEquals(36, Fraction::TRIPLE->of(12));
        $this->assertEquals(9, Fraction::THREE_QUARTER->of(12));
        $this->assertEquals(3, Fraction::QUARTER->of(12));
        $this->assertEquals(8, Fraction::TWO_THIRDS->of(12));
        $this->assertEquals(4, Fraction::THIRD->of(12));
        $this->assertEquals(6, Fraction::HALF->of(12));
    }
}
