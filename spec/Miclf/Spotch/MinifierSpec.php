<?php

namespace spec\Miclf\Spotch;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MinifierSpec extends ObjectBehavior
{
    /**
     * Test that the class can be instantiated correctly.
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType('Miclf\Spotch\Minifier');
    }
}
