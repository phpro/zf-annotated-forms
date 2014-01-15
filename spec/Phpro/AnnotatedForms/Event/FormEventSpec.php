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

    /**
     * @param stdClass $subject
     */
    public function it_should_be_able_to_create_instance($subject)
    {
        $event = $this->create('name', $subject, array('param1' => true));

        $event->shouldBeAnInstanceOf('Phpro\AnnotatedForms\Event\FormEvent');
        $event->getName()->shouldBe('name');
        $event->getTarget()->shouldReturn($subject);
        $event->getParam('param1')->shouldBe(true);
    }
}
