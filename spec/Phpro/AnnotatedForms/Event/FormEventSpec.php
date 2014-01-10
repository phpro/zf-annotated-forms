<?php

namespace spec\Phpro\AnnotatedForms\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormEventSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Event\FormEvent');
    }

    public function it_extends_from_zend_event()
    {
        $this->shouldHaveType('Zend\EventManager\Event');
    }
}
