<?php

namespace Phpro\AnnotatedForms\Event;


use Zend\EventManager\Event;


/**
 * Class FormEvent
 *
 * @package Phpro\AnnotatedForms\Event
 */
class FormEvent extends Event
{

    const EVENT_FORM_SPECIFICATIONS_PRE = 'getFormSpecifications.pre';
    const EVENT_FORM_SPECIFICATIONS_POST = 'getFormSpecifications.post';

    const EVENT_CONFIGURE_FORM_PRE = 'configureForm.pre';
    const EVENT_CONFIGURE_FORM_POST = 'configureForm.post';
    const EVENT_CONFIGURE_FIELDSET_PRE = 'configureFieldset.pre';
    const EVENT_CONFIGURE_FIELDSET_POST = 'configureFieldset.post';
    const EVENT_CONFIGURE_ELEMENT_PRE = 'configureElement.pre';
    const EVENT_CONFIGURE_ELEMENT_POST = 'configureElement.post';

} 