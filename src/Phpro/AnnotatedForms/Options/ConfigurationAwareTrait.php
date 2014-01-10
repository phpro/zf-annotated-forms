<?php

namespace Phpro\AnnotatedForms\Options;

/**
 * Class ConfigurationAwareTrait
 *
 * @package Phpro\AnnotatedForms\Options
 */
trait ConfigurationAwareTrait
{

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return \Phpro\AnnotatedForms\Options\Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

} 