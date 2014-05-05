<?php

namespace spec\Phpro\AnnotatedForms\Listener;

use Phpro\AnnotatedForms\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SetEntityAsObjectListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Listener\SetEntityAsObjectListener');
    }

    public function it_should_extend_abstractListenerAggregate()
    {
        $this->shouldHaveType('Zend\EventManager\AbstractListenerAggregate');
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $eventManager
     */
    public function it_should_attach_a_listener_on_formCreate_post($eventManager)
    {
        $this->attach($eventManager);
        $eventManager->attach(FormEvent::EVENT_CONFIGURE_FORM_POST, Argument::cetera())->shouldHaveBeenCalled();
    }

    /**
     * @param \Phpro\AnnotatedForms\Event\FormEvent $event
     * @param \Zend\Form\Form $form
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function it_should_attach_an_entity_as_object($event, $form, $configuration)
    {
        $event->getTarget()->willReturn($form);
        $event->getConfiguration()->willReturn($configuration);
        $configuration->getEntity()->willReturn('stdClass');
        $form->setObject(Argument::type('stdClass'))->shouldBeCalled();

        $this->setEntityAsObject($event);
    }
}
