<?php

namespace Phpro\AnnotatedForms\Options;

/**
 * Interface ConfigurationAwareInterface
 *
 * @package Phpro\AnnotatedForms\Options
 */
interface ConfigurationAwareInterface
{

    /**
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function setConfiguration($configuration);

    /**
     * @return \Phpro\AnnotatedForms\Options\Configuration
     */
    public function getConfiguration();

} 