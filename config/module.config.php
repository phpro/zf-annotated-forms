<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'Phpro\AnnotatedForms\Form\Annotation\Builder' => 'Phpro\AnnotatedForms\Form\Annotation\Builder',
            'Phpro\AnnotatedForms\Form\Factory' => 'Phpro\AnnotatedForms\Form\Factory',
        ),
        'factories' => array(
            'Phpro\AnnotatedForms\Listener\CollectionElementListener' => 'Phpro\AnnotatedForms\Listener\CollectionElementListener',
            'Phpro\AnnotatedForms\Service\ConfigurationFactory' => 'Phpro\AnnotatedForms\Service\ConfigurationFactory',
        ),
        'abstract_factories' => array(
            'Phpro\AnnotatedForms\Service\AbstractFormFactory',
        ),
        'shared' => array(
            'Phpro\AnnotatedForms\Form\Annotation\Builder' => false,
            'Phpro\AnnotatedForms\Form\Factory' => false,
        ),
    ),
);