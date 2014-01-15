<?php

namespace spec\Phpro\AnnotatedForms\Listener;

use Phpro\AnnotatedForms\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PrepareListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Listener\PrepareListener');
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
        $eventManager->attach(FormEvent::EVENT_FORM_CREATE_POST, Argument::cetera())->shouldHaveBeenCalled();
    }

    /**
     * @param \Phpro\AnnotatedForms\Event\FormEvent $event
     * @param \Zend\Form\Form $form
     */
    public function it_should_prepare_forms($event, $form)
    {
        $event->getTarget()->willReturn($form);
        $this->prepareForm($event);

        $form->prepare()->shouldBeCalled();
    }
}
