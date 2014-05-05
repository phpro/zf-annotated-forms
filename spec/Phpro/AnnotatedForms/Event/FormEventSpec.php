<?php

namespace spec\Phpro\AnnotatedForms\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Phpro\AnnotatedForms\Options\ProvidesConfigurationTraitSpec;

class FormEventSpec extends ObjectBehavior
{

    use ProvidesConfigurationTraitSpec;

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
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     *
     */
    public function it_should_be_able_to_create_instance($subject, $configuration)
    {
        $event = $this->create('name', $subject, $configuration, array('param1' => true));

        $event->shouldBeAnInstanceOf('Phpro\AnnotatedForms\Event\FormEvent');
        $event->getName()->shouldBe('name');
        $event->getTarget()->shouldReturn($subject);
        $event->getConfiguration()->shouldReturn($configuration);
        $event->getParam('param1')->shouldBe(true);
    }
}
