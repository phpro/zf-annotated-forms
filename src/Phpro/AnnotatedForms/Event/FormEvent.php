<?php

namespace Phpro\AnnotatedForms\Event;


use Phpro\AnnotatedForms\Options\Configuration;
use Phpro\AnnotatedForms\Options\ConfigurationAwareInterface;
use Phpro\AnnotatedForms\Options\ConfigurationAwareTrait;
use Zend\EventManager\Event;


/**
 * Class FormEvent
 *
 * @package Phpro\AnnotatedForms\Event
 */
class FormEvent extends Event
    implements ConfigurationAwareInterface
{

    // Form AnnotationBuilder events:
    const EVENT_FORM_CREATE_PRE = 'createForm.pre';
    const EVENT_FORM_CREATE_POST = 'createForm.post';
    const EVENT_FORM_SPECIFICATIONS_PRE = 'getFormSpecifications.pre';
    const EVENT_FORM_SPECIFICATIONS_POST = 'getFormSpecifications.post';

    // Form Factory events:
    const EVENT_CONFIGURE_FORM_PRE = 'configureForm.pre';
    const EVENT_CONFIGURE_FORM_POST = 'configureForm.post';
    const EVENT_CONFIGURE_FIELDSET_PRE = 'configureFieldset.pre';
    const EVENT_CONFIGURE_FIELDSET_POST = 'configureFieldset.post';
    const EVENT_CONFIGURE_ELEMENT_PRE = 'configureElement.pre';
    const EVENT_CONFIGURE_ELEMENT_POST = 'configureElement.post';

    use ConfigurationAwareTrait;

    /**
     * @param $name
     * @param $subject
     * @param Configuration $configuration
     * @param array $params
     *
     * @return FormEvent
     */
    public static function create($name, $subject, $configuration, $params = array())
    {
        $event = new self($name, $subject);
        $event->setConfiguration($configuration);
        foreach ($params as $key => $value) {
            $event->setParam($key, $value);
        }
        return $event;
    }

}
