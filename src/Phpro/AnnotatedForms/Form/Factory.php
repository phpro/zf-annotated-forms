<?php

namespace Phpro\AnnotatedForms\Form;

use Phpro\AnnotatedForms\Event\FormEvent;
use Phpro\AnnotatedForms\Options\ConfigurationAwareInterface;
use Phpro\AnnotatedForms\Options\ConfigurationAwareTrait;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ProvidesEvents;
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
    implements EventManagerAwareInterface, ConfigurationAwareInterface
{

    use ConfigurationAwareTrait, ProvidesEvents;

    /**
     * @param       $name
     * @param       $subject
     * @param array $params
     */
    protected function triggerEvent($name, $subject, $params = array())
    {
        $event = FormEvent::create($name, $subject, $this->getConfiguration(), $params);
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
