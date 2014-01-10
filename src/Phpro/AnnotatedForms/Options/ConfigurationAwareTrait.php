<?php

namespace Phpro\AnnotatedForms\Configuration;

/**
 * Class ConfigurationAwareTrait
 *
 * @package Phpro\AnnotatedForms\Configuration
 */
trait ConfigurationAwareTrait
{

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @param \Phpro\AnnotatedForms\Configuration\Configuration $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return \Phpro\AnnotatedForms\Configuration\Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

} 