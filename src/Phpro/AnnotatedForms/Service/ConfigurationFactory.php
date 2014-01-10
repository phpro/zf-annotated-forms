<?php

namespace Phpro\AnnotatedForms\Service;

use Phpro\AnnotatedForms\Options\Configuration;
use Phpro\AnnotatedForms\Exception\RuntimeException;
use Zend\Cache\Storage\StorageInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

/**
 * Class ConfigurationFactory
 *
 * @package Phpro\AnnotatedForms\Service
 */
class ConfigurationFactory
    implements FactoryInterface, ServiceLocatorAwareInterface
{

    const CONFIG_DEFAULT_NAMESPACE = 'zf-annotated-forms';
    const CONFIG_DEFAULT_CONFIG_KEY = 'defaults';

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);

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
        $config = new ArrayObject(array_merge_recursive($defaults, $options));
        $this->injectDependencies($config);

        return new Configuration($config);
    }

    /**
     * @param ArrayObject $config
     */
    protected function injectDependencies(ArrayObject $config)
    {
        $this->injectCache($config);
        $this->injectInitializers($config);
        $this->injectListeners($config);
    }

    /**
     * @param ArrayObject $config
     *
     * @throws \Phpro\AnnotatedForms\Exception\RuntimeException
     */
    protected function injectCache(ArrayObject $config)
    {
        if (!($serviceKey = $config->offsetGet('cache'))) {
            return;
        }

        $cache = $this->getServiceLocator()->get($serviceKey);
        if (!$cache instanceof StorageInterface) {
            throw new RuntimeException(sprintf('Expected StorageInterface for key "%s"', $serviceKey));
        }

        $config->offsetSet('cache', $cache);
    }

    /**
     * @param ArrayObject $config
     *
     * @throws \Phpro\AnnotatedForms\Exception\RuntimeException
     */
    protected function injectInitializers(ArrayObject $config)
    {
        if (!$config->offsetGet('initializers')) {
            return;
        }

        $initializers = $config->offsetGet('initializers');
        foreach ($initializers as $index => $serviceKey) {
            $initializer = $this->getServiceLocator()->get($serviceKey);
            if (!$initializer instanceof InitializerInterface) {
                throw new RuntimeException(sprintf('Expected InitializerInterface for key "%s"', $serviceKey));
            }

            $initializers->offsetSet($index, $initializer);
        }
    }

    /**
     * @param ArrayObject $config
     *
     * @throws \Phpro\AnnotatedForms\Exception\RuntimeException
     */
    protected function injectListeners(ArrayObject $config)
    {
        if (!$config->offsetGet('listeners')) {
            return;
        }

        $listeners = $config->offsetGet('listeners');
        foreach ($listeners as $index => $serviceKey) {
            $listener = $this->getServiceLocator()->get($serviceKey);
            if (!$listener instanceof ListenerAggregateInterface) {
                throw new RuntimeException(sprintf('Expected ListenerAggregateInterface for key "%s"', $serviceKey));
            }

            $listeners->offsetSet($index, $listener);
        }
    }

}