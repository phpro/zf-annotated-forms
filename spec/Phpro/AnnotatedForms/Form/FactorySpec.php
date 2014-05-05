<?php

namespace spec\Phpro\AnnotatedForms\Form;

use Phpro\AnnotatedForms\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Phpro\AnnotatedForms\Options\ProvidesConfigurationTraitSpec;

class FactorySpec extends ObjectBehavior
{

    use ProvidesConfigurationTraitSpec;

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function let($eventManager)
    {
        $this->setEventManager($eventManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Form\Factory');
    }

    public function it_should_extend_zend_form_factory()
    {
        $this->shouldHaveType('Zend\Form\Factory');
    }

    public function it_should_be_aware_of_the_eventmanager()
    {
        $this->shouldImplement('Zend\EventManager\EventManagerAwareInterface');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_have_eventManager($eventManager)
    {
        $this->setEventManager($eventManager);
        $this->getEventManager()->shouldReturn($eventManager);
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\ElementInterface $element
     * @param \ArrayAccess $spec
     */
    public function it_should_trigger_events_during_field_configuration($eventManager, $element, $spec)
    {
        $this->configureElement($element, $spec);
        $triggeredEvents = array(FormEvent::EVENT_CONFIGURE_ELEMENT_PRE, FormEvent::EVENT_CONFIGURE_ELEMENT_POST);
        $eventManager->trigger(Argument::that(function($event) use ($element, $spec, $triggeredEvents) {
            return (
                $event instanceof FormEvent
                && in_array($event->getName(), $triggeredEvents)
                && $event->getTarget() == $element->getWrappedObject()
                && $event->getParam('spec') == $spec->getWrappedObject()
            );
        }))->shouldBeCalledTimes(count($triggeredEvents));
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\FieldsetInterface $fieldset
     * @param \ArrayAccess $spec
     */
    public function it_should_trigger_events_during_fieldset_configuration($eventManager, $fieldset, $spec)
    {
        $this->configureFieldset($fieldset, $spec);
        $triggeredEvents = array(FormEvent::EVENT_CONFIGURE_FIELDSET_PRE, FormEvent::EVENT_CONFIGURE_FIELDSET_POST);
        $eventManager->trigger(Argument::that(function($event) use ($fieldset, $spec, $triggeredEvents) {
            return (
                $event instanceof FormEvent
                && in_array($event->getName(), $triggeredEvents)
                && $event->getTarget() == $fieldset->getWrappedObject()
                && $event->getParam('spec') == $spec->getWrappedObject()
            );
        }))->shouldBeCalledTimes(count($triggeredEvents));
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\FormInterface $form
     * @param \ArrayAccess $spec
     */
    public function it_should_trigger_events_during_form_configuration($eventManager, $form, $spec)
    {
        $this->configureForm($form, $spec);
        $triggeredEvents = array(FormEvent::EVENT_CONFIGURE_ELEMENT_PRE, FormEvent::EVENT_CONFIGURE_ELEMENT_POST);
        $eventManager->trigger(Argument::that(function($event) use ($form, $spec, $triggeredEvents) {
            return (
                $event instanceof FormEvent
                && in_array($event->getName(), $triggeredEvents)
                && $event->getTarget() == $form->getWrappedObject()
                && $event->getParam('spec') == $spec->getWrappedObject()
            );
        }))->shouldBeCalledTimes(count($triggeredEvents));
    }


}
