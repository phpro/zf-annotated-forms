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

} 