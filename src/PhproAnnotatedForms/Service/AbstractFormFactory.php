<?php

namespace PhproAnnotatedForms\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractFormFactory
 *
 * @package PhproAnnotatedForms\Service
 */
class AbstractFormFactory implements AbstractFactoryInterface
{

    const FACTORY_NAMESPACE = 'annotated_forms';

    /**
     * Cache of canCreateServiceWithName lookups
     * @var array
     */
    protected $lookupCache = array();

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (array_key_exists($requestedName, $this->lookupCache)) {
            return $this->lookupCache[$requestedName];
        }

        if (!$serviceLocator->has('Config')) {
            return false;
        }

        // Validate object is set
        $config = $serviceLocator->get('Config');
        $namespace = self::FACTORY_NAMESPACE;
        if (!isset($config[$namespace]) || !is_array($config[$namespace]) || !isset($config[$namespace][$requestedName])) {
            $this->lookupCache[$requestedName] = false;
            return false;
        }

        $this->lookupCache[$requestedName] = true;
        return true;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $options = $serviceLocator->get('Config');
        $options = $options[self::FACTORY_NAMESPACE][$requestedName];

        /** @var ConfigurationFactory $configFactory */
        $configFactory = $serviceLocator->get('PhproAnnotatedForms\Service\ConfigurationFactory');
        $config = $configFactory->createConfiguration($options);

        return $form;
    }


} 