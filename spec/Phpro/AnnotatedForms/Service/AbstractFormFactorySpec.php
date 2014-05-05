<?php

namespace spec\Phpro\AnnotatedForms\Service;

use Phpro\AnnotatedForms\Service\AbstractFormFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class AbstractFormFactorySpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Service\AbstractFormFactory');
    }

    public function it_should_implement_zend_abstract_factory_interface()
    {
        $this->shouldImplement('Zend\ServiceManager\AbstractFactoryInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_be_able_to_create_annotated_forms($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);
        $name = 'annotated-form';
        $this->canCreateServiceWithName($serviceLocator, $name, $name)->shouldReturn(true);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_not_able_to_create_annotated_forms($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);
        $name = 'other-object';
        $this->canCreateServiceWithName($serviceLocator, $name, $name)->shouldReturn(false);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface$serviceLocator
     * @param \Phpro\AnnotatedForms\Service\ConfigurationFactory $configurationService
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     * @param \Phpro\AnnotatedForms\Form\Factory $formFactory
     * @param \Zend\Form\Form $form
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function it_should_create_annotated_services($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $form, $configuration)
    {
        $this->mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $configuration);
        $annotationBuilder->createForm('entity')->willReturn($form);

        $name = 'annotated-form';
        $this->createServiceWithName($serviceLocator, $name, $name)->shouldReturn($form);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface$serviceLocator
     * @param \Phpro\AnnotatedForms\Service\ConfigurationFactory $configurationService
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     * @param \Phpro\AnnotatedForms\Form\Factory $formFactory
     * @param \Zend\Form\Form $form
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     * @param \Zend\Form\FormElementManager $elementManager
     * @param \Zend\ServiceManager\InitializerInterface $initializer
     */
    public function it_should_add_initializers_to_elementManager($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $form, $configuration, $elementManager, $initializer)
    {
        $this->mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $configuration);
        $annotationBuilder->createForm('entity')->willReturn($form);

        // Set correct object states:
        $configuration->getInitializers()->willReturn(array($initializer));
        $formFactory->getFormElementManager()->willReturn($elementManager);
        $formFactory->setEventManager(Argument::any())->willReturn(true);
        $formFactory->setConfiguration(Argument::any())->shouldBeCalled();

        // Call service
        $name = 'annotated-form';
        $this->createServiceWithName($serviceLocator, $name, $name)->shouldReturn($form);
        $elementManager->addInitializer($initializer)->shouldBeCalled();
        $elementManager->setServiceLocator($serviceLocator)->shouldBeCalled();
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface$serviceLocator
     * @param \Phpro\AnnotatedForms\Service\ConfigurationFactory $configurationService
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     * @param \Phpro\AnnotatedForms\Form\Factory $formFactory
     * @param \Zend\Form\Form $form
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\EventManager\ListenerAggregateInterface $listener
     */
    public function it_should_add_listeners_to_eventManager($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $form, $configuration, $eventManager, $listener)
    {
        $this->mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $configuration);
        $annotationBuilder->createForm('entity')->willReturn($form);

        // Set correct object states:
        $configuration->getListeners()->willReturn(array($listener));
        $annotationBuilder->getEventManager()->willReturn($eventManager);
        $formFactory->setEventManager($eventManager)->willReturn();
        $formFactory->setConfiguration(Argument::any())->shouldBeCalled();

        $name = 'annotated-form';
        $this->createServiceWithName($serviceLocator, $name, $name)->shouldReturn($form);
        $eventManager->attach($listener)->shouldBeCalled();
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface$serviceLocator
     * @param \Phpro\AnnotatedForms\Service\ConfigurationFactory $configurationService
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     * @param \Zend\Form\Form $form
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function it_should_add_configuration_to_form_factory($serviceLocator, $configurationService, $annotationBuilder, $form, $configuration)
    {
        // Make sure form factory implements ConfigurationAwareInterface
        $prophet = new Prophet();
        $formFactory = $prophet->prophesize('Phpro\AnnotatedForms\Form\Factory');
        $formFactory->willImplement('Phpro\AnnotatedForms\Options\ConfigurationAwareInterface');
        $formFactory->setConfiguration(Argument::any())->willReturn();

        $this->mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $configuration);
        $annotationBuilder->createForm('entity')->willReturn($form);

        $name = 'annotated-form';
        $this->createServiceWithName($serviceLocator, $name, $name)->shouldReturn($form);
        $formFactory->setConfiguration($configuration)->shouldBeCalled();
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface$serviceLocator
     * @param \Phpro\AnnotatedForms\Service\ConfigurationFactory $configurationService
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     * @param \Zend\Form\Form $form
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function it_should_add_eventManager_to_form_factory($serviceLocator, $configurationService, $annotationBuilder, $form, $configuration)
    {
        // Make sure form factory implements ConfigurationAwareInterface
        $prophet = new Prophet();
        $formFactory = $prophet->prophesize('Phpro\AnnotatedForms\Form\Factory');
        $formFactory->willImplement('Zend\EventManager\EventManagerAwareInterface');

        $this->mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $configuration);
        $annotationBuilder->createForm('entity')->willReturn($form);

        $name = 'annotated-form';
        $this->createServiceWithName($serviceLocator, $name, $name)->shouldReturn($form);
        $formFactory->setEventManager($configuration)->shouldBeCalled();
        $formFactory->setConfiguration(Argument::any())->shouldBeCalled();
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    protected function mockConfiguration($serviceLocator)
    {
        $serviceLocator->has('Config')->willReturn(true);
        $serviceLocator->get('Config')->willReturn(array(
            AbstractFormFactory::FACTORY_NAMESPACE => array(
                'annotated-form' => array(
                    'initializers' => array(
                        'initializer'
                    ),
                    'listeners' => array(
                        'listener'
                    ),
                    'entity' => 'entity',
                ),
            )
       ));
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface$serviceLocator
     * @param \Phpro\AnnotatedForms\Service\ConfigurationFactory $configurationService
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     * @param \Phpro\AnnotatedForms\Form\Factory $formFactory
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    protected function mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $configuration)
    {
        $prophet = new Prophet();

        // Mock event manager
        $eventManager = $prophet->prophesize('Zend\EventManager\EventManager');
        $eventManager->attach(Argument::cetera())->willReturn(true);

        // Mock configurtion factory
        $serviceLocator->has('Phpro\AnnotatedForms\Service\ConfigurationFactory')->willReturn(true);
        $serviceLocator->get('Phpro\AnnotatedForms\Service\ConfigurationFactory')->willReturn($configurationService);
        $configurationService->createConfiguration(Argument::any())->willReturn($configuration);

        // Mock configuration
        $configuration->getListeners()->willReturn(array());
        $configuration->getInitializers()->willReturn(array());
        $configuration->getEntity()->willReturn('entity');

        // Mock Annotation builder
        $serviceLocator->has('Phpro\AnnotatedForms\Form\Annotation\Builder')->willReturn(true);
        $serviceLocator->get('Phpro\AnnotatedForms\Form\Annotation\Builder')->willReturn($annotationBuilder);
        $annotationBuilder->setConfiguration(Argument::any())->willReturn(true);
        $annotationBuilder->getEventManager()->willReturn($eventManager->reveal());
        $annotationBuilder->setFormFactory($formFactory)->willReturn(true);

        // Mock Form Factory
        $serviceLocator->has('Phpro\AnnotatedForms\Form\Factory')->willReturn(true);
        $serviceLocator->get('Phpro\AnnotatedForms\Form\Factory')->willReturn($formFactory);
        $formFactory->setEventManager($eventManager)->willReturn(true);
        $formFactory->setConfiguration(Argument::any())->shouldBeCalled();

        // Mock defalt elementManager
        $elementManager = $prophet->prophesize('Zend\Form\FormElementManager');
        $formFactory->getFormElementManager()->willReturn($elementManager->reveal());

        $this->mockConfiguration($serviceLocator);
    }

}
