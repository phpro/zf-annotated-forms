<?php

namespace Phpro\AnnotatedForms\Form;

use Phpro\AnnotatedForms\Event\FormEvent;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\Factory as ZendFormFactory;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;

/**
 * Class Factory
 *
 * @package Phpro\AnnotatedForms\Form
 */
class Factory extends ZendFormFactory
    implements EventManagerAwareInterface
{

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(__CLASS__, get_class($this));
        $events->setIdentifiers($identifiers);
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    /**
     * @param $eventName
     * @param $subject
     * @param $params
     *
     * @return void
     */
    protected function triggerEvent($eventName, $subject, $params = array())
    {
        $event = new FormEvent($eventName, $subject);
        foreach ($params as $key => $value) {
            $event->setParam($key, $value);
        }

        $this->getEventManager()->trigger($event);
    }

    /**
     * {@inheritDoc}
     */
    public function configureElement(ElementInterface $element, $spec)
    {
        $this->triggerEvent(FormEvent::EVENT_CONFIGURE_ELEMENT_PRE, $element, array('spec' => $spec));
        parent::configureElement($element, $spec);
        $this->triggerEvent(FormEvent::EVENT_CONFIGURE_ELEMENT_POST, $element, array('spec' => $spec));

        return $element;
    }

    /**
     * {@inheritDoc}
     */
    public function configureFieldset(FieldsetInterface $fieldset, $spec)
    {
        $this->triggerEvent(FormEvent::EVENT_CONFIGURE_FIELDSET_PRE, $fieldset, array('spec' => $spec));
        parent::configureFieldset($fieldset, $spec);
        $this->triggerEvent(FormEvent::EVENT_CONFIGURE_FIELDSET_POST, $fieldset, array('spec' => $spec));

        return$fieldset;
    }

    /**
     * {@inheritDoc}
     */
    public function configureForm(FormInterface $form, $spec)
    {
        $this->triggerEvent(FormEvent::EVENT_CONFIGURE_FORM_PRE, $form, array('spec' => $spec));
        parent::configureForm($form, $spec);
        $this->triggerEvent(FormEvent::EVENT_CONFIGURE_FORM_POST, $form, array('spec' => $spec));

        return $form;
    }

}