<?php

namespace Phpro\AnnotatedForms\Listener;


use Phpro\AnnotatedForms\Event\FormEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\FormInterface;

/**
 * Class PrepareListener
 *
 * @package Phpro\AnnotatedForms\Listener
 */
class PrepareListener extends AbstractListenerAggregate
{

    const PRIORITY = -1000;

    /**
     * {@inheritDocs}
     */
    public function attach(EventManagerInterface $events)
    {
       $this->listeners[] = $events->attach(FormEvent::EVENT_FORM_CREATE_POST, array($this, 'prepareForm'), self::PRIORITY);
    }

    /**
     * Make sure that a form is prepared after loading.
     * This will make sure that collections and input filters are working as suspected.
     *
     * @param FormEvent $e
     */
    public function prepareForm(FormEvent $e)
    {
        $form = $e->getTarget();
        if (!$form instanceof FormInterface) {
            return;
        }

        $form->prepare();
    }


} 