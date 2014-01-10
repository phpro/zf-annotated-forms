<?php
return array(

    /*
     * Set default values:
     */
    'zf-annotated-forms' => array(
        'defaults' => array(
            'initializers' => array(),
            'listeners' => array(),
            'cache' => null,
            'cache_key' => '',
            'entity' => '',
        ),
    ),

    /*
     * Create new annotated forms
     */
    'annotated_forms' => array(
        'form-key' => array(
            'initializers' => array(),
            'listeners' => array(),
            'cache' => null,
            'cache_key' => 'cached-form-key',
            'entity' => '',
        )
    )

);