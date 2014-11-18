<?php namespace spec\Miclf\Spotch;

use PhpSpec\ObjectBehavior;

class BookmarklerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Miclf\Spotch\Bookmarkler');
    }

    public function it_can_make_a_bookmarklet_from_a_string()
    {
        $source   = 'var foo="bar";';
        $expected = 'javascript:(function(){var%20foo%3D%22bar%22%3B})();';

        $this->make($source)->shouldReturn($expected);
    }
}
