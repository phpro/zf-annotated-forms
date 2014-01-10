<?php

namespace Phpro\AnnotatedForms\Configuration;

/**
 * Interface ConfigurationAwareInterface
 *
 * @package Phpro\AnnotatedForms\Configuration
 */
interface ConfigurationAwareInterface
{

    /**
     * @param \Phpro\AnnotatedForms\Configuration\Configuration $configuration
     */
    public function setConfiguration($configuration);

    /**
     * @return \Phpro\AnnotatedForms\Configuration\Configuration
     */
    public function getConfiguration();

} 