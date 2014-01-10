<?php

namespace spec\Phpro\AnnotatedForms\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuntimeExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Exception\RuntimeException');
    }

    public function it_extends_from_exception()
    {
        $this->shouldHaveType('Exception');
    }
}
