<?php

namespace PhproAnnotatedForms\Service;

use PhproAnnotatedForms\Configuration\Configuration;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ConfigurationFactory
 *
 * @package PhproAnnotatedForms\Service
 */
class ConfigurationFactory implements FactoryInterface
{

    const CONFIG_DEFAULT_NAMESPACE = 'zf-annotated-forms';
    const CONFIG_DEFAULT_CONFIG_KEY = 'defaults';

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        if (isset($config[self::CONFIG_DEFAULT_NAMESPACE][self::CONFIG_DEFAULT_CONFIG_KEY])) {
            $defaults = $config[self::CONFIG_DEFAULT_NAMESPACE][self::CONFIG_DEFAULT_CONFIG_KEY];
            $this->setDefaults($defaults);
        }

        return $this;
    }

    /**
     * @param array $defaults
     */
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param array $options
     *
     * @return array
     */
    public function getOptions(ServiceLocatorInterface $sl, array $options)
    {
        $config = $sl->get('Config');
        if (!isset($config[self::CONFIG_DEFAULT_NAMESPACE][self::CONFIG_DEFAULT_CONFIG_KEY])) {
            return array();
        }

        $defaults = $config[self::CONFIG_DEFAULT_NAMESPACE][self::CONFIG_DEFAULT_CONFIG_KEY];
        $options = array_merge($defaults, $options);

        return $options;
    }


    /**
     * @param array $options
     *
     * @return Configuration
     */
    public function createConfiguration(array $options)
    {
        $defaults = $this->getDefaults();
        $config = array_merge_recursive($defaults, $options);

        return new Configuration($config);
    }

}