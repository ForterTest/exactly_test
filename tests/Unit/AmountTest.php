<?php

namespace Tests\Unit;

use Domain\Types\Amount;
use Tests\TestCase;

class AmountTest extends TestCase
{
    public function testMinorToMajor()
    {
        $amount = new Amount();

        $this->assertEquals($amount->fromMinor(123.45)->getMajor(), 12345);
        $this->assertEquals($amount->fromMinor(-123.45)->getMajor(), -12345);
    }

    public function testMajorToMinor()
    {
        $amount = new Amount();

        $this->assertEquals($amount->fromMajor(12345)->getMinor(), 123.45);
        $this->assertEquals($amount->fromMajor(-12345)->getMinor(), -123.45);
    }
}
