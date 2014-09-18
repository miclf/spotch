<?php namespace spec\Miclf\Spotch;

use PhpSpec\ObjectBehavior;

class MinifierSpec extends ObjectBehavior
{
    /**
     * Test that the class can be instantiated correctly.
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType('Miclf\Spotch\Minifier');
    }

    public function it_trims_lines()
    {
        $this->trimLines("    var foo = 'bar';    ")
        ->shouldReturn("var foo = 'bar';");
    }

    public function it_removes_empty_lines()
    {
        $this->removeEmptyLines("

            var foo = 'bar';

            var baz = 'boom';

        ")
        ->shouldReturn(
"            var foo = 'bar';
            var baz = 'boom';"
        );
    }

    public function it_removes_new_line_characters()
    {
        $this->removeNewlines("

var foo = 'bar';

var baz = 'boom';"

        )
        ->shouldReturn(
            "var foo = 'bar';var baz = 'boom';"
        );
    }

    public function it_removes_single_line_comments_that_are_on_their_own_line()
    {
        $this->removeComments(
           "// A comment on its own line.
            var foo = 'bar';
        ")->shouldReturn("
            var foo = 'bar';
        ");
    }

    public function it_removes_single_line_comments_at_the_end_of_a_line()
    {
        $this->removeComments("var foo = 'bar';// A comment after some code.")
        ->shouldReturn("var foo = 'bar';");
    }

    public function it_does_not_accidentally_modify_urls()
    {
        $code = "'http://example.com';";

        $this->minify($code)->shouldReturn($code);
    }

    public function it_does_not_accidentally_modify_regex()
    {
        $code = "/foo bar/;";

        $this->minify($code)->shouldReturn($code);
    }

    public function it_does_not_accidentally_modify_regex_containing_a_slash()
    {
        $code = '/foo\/bar/';

        $this->minify($code)->shouldReturn($code);
    }

    public function it_removes_spaces_around_if()
    {
        $this->removeOptionalSpaces(' if (foo){return baz};')
        ->shouldReturn('if(foo){return baz};');
    }

    public function it_removes_spaces_around_else()
    {
        $this->removeOptionalSpaces(' else {return baz};')
        ->shouldReturn('else{return baz};');
    }

    public function it_removes_spaces_around_plus_signs()
    {
        $this->removeOptionalSpaces('foo  +  bar')
        ->shouldReturn('foo+bar');
    }

    public function it_removes_spaces_around_equal_signs()
    {
        $this->removeOptionalSpaces('var foo = "bar";')
        ->shouldReturn('var foo="bar";');
    }

    public function it_removes_spaces_around_double_equal_signs()
    {
        $this->removeOptionalSpaces('foo == "bar";')
        ->shouldReturn('foo=="bar";');
    }

    public function it_removes_spaces_around_colons()
    {
        $this->removeOptionalSpaces('foo : "bar";')
        ->shouldReturn('foo:"bar";');
    }

    public function it_removes_spaces_around_commas()
    {
        $this->removeOptionalSpaces('foo(bar, baz);')
        ->shouldReturn('foo(bar,baz);');
    }

    public function it_removes_spaces_around_opening_parenthesis()
    {
        $this->removeOptionalSpaces('foo ( bar')
        ->shouldReturn('foo(bar');
    }

    public function it_removes_spaces_around_closing_parenthesis()
    {
        $this->removeOptionalSpaces('foo ) bar')
        ->shouldReturn('foo)bar');
    }

    public function it_removes_spaces_around_opening_curly_braces()
    {
        $this->removeOptionalSpaces(') { foo')
        ->shouldReturn('){foo');
    }

    public function it_removes_spaces_around_closing_curly_braces()
    {
        $this->removeOptionalSpaces('foo } bar')
        ->shouldReturn('foo}bar');
    }

    public function it_removes_spaces_around_and_operators()
    {
        $this->removeOptionalSpaces('foo && bar')
        ->shouldReturn('foo&&bar');
    }

    public function it_removes_spaces_around_or_operators()
    {
        $this->removeOptionalSpaces('foo || bar')
        ->shouldReturn('foo||bar');
    }
}
