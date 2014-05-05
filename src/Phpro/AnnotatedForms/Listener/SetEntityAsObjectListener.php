<?php

namespace Phpro\AnnotatedForms\Listener;


use Phpro\AnnotatedForms\Event\FormEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;

/**
 * Class PrepareListener
 *
 * @package Phpro\AnnotatedForms\Listener
 */
class SetEntityAsObjectListener extends AbstractListenerAggregate
{

    const PRIORITY = 2000;

    /**
     * {@inheritDocs}
     */
    public function attach(EventManagerInterface $events)
    {
       $this->listeners[] = $events->attach(FormEvent::EVENT_CONFIGURE_FORM_POST, array($this, 'setEntityAsObject'), self::PRIORITY);
    }

    /**
     * @param FormEvent $e
     */
    public function setEntityAsObject(FormEvent $e)
    {
        $form = $e->getTarget();
        if (!$form instanceof FormInterface) {
            return;
        }

        $config = $e->getConfiguration();
        if (!$config || ! $config->getEntity()) {
            return;
        }

        $rc = new \ReflectionClass($config->getEntity());
        $instance = $rc->newInstanceWithoutConstructor();
        $form->setObject($instance);
    }

}
