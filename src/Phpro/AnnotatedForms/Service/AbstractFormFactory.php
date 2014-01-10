<?php

namespace Phpro\AnnotatedForms\Service;

use Phpro\AnnotatedForms\Configuration\Configuration;
use Phpro\AnnotatedForms\Configuration\ConfigurationAwareInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractFormFactory
 *
 * @package Phpro\AnnotatedForms\Service
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
        $configFactory = $serviceLocator->get('Phpro\AnnotatedForms\Service\ConfigurationFactory');
        $config = $configFactory->createConfiguration($options);

        // Create form:
        $entity = $config->getEntity();
        $annotationBuilder = $this->loadAnnotationBuilder($serviceLocator, $config);
        $form = $annotationBuilder->createForm($entity);

        return $form;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param Configuration           $config
     *
     * @return \Phpro\AnnotatedForms\Form\Annotation\Builder
     */
    protected function loadAnnotationBuilder(ServiceLocatorInterface $serviceLocator, Configuration $config)
    {
        /** @var \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder */
        $annotationBuilder = $serviceLocator->get('Phpro\AnnotatedForms\Form\Annotation\Builder');
        $annotationBuilder->setConfiguration($config);
        $eventManager = $annotationBuilder->getEventManager();

        // Add form factory
        $formFactory = $this->loadFormFactory($serviceLocator, $config);
        $annotationBuilder->setFormFactory($formFactory);

        // Configure Form Builder
        if ($formFactory instanceof ConfigurationAwareInterface) {
            $formFactory->setConfiguration($config);
        }
        if ($formFactory instanceof EventManagerAwareInterface) {
            $formFactory->setEventManager($eventManager);
        }

        // Attach listeners
        foreach ($config->getListeners() as $listener) {
            $eventManager->attach($listener);
        }

        return $annotationBuilder;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param Configuration           $config
     *
     * @return \Phpro\AnnotatedForms\Form\Factory
     */
    protected function loadFormFactory(ServiceLocatorInterface $serviceLocator, Configuration $config)
    {
        /** @var \Phpro\AnnotatedForms\Form\Factory $formFactory */
        $formFactory = $serviceLocator->get('Phpro\AnnotatedForms\Form\Factory');

        // Add custom form element initializers
        $elementManager = $formFactory->getFormElementManager();
        foreach ($config->getInitializers() as $initializer) {
            $elementManager->addInitializer($initializer);
        }

        return $formFactory;
    }

} 