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
     */
    public function it_should_create_annotated_services($serviceLocator, $configurationService, $annotationBuilder, $formFactory, $form)
    {
        $this->mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory);
        $annotationBuilder->createForm('entity')->willReturn($form);

        $name = 'annotated-form';
        $this->createServiceWithName($serviceLocator, $name, $name)->shouldReturn($form);
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
     */
    protected function mockServices($serviceLocator, $configurationService, $annotationBuilder, $formFactory)
    {
        $prophet = new Prophet();
        $eventManager = $prophet->prophesize('Zend\Eventmanager\EventManager');
        $eventManager->attach(Argument::cetera())->willReturn(true);

        $serviceLocator->has('Phpro\AnnotatedForms\Service\ConfigurationFactory')->willReturn(true);
        $serviceLocator->has('Phpro\AnnotatedForms\Service\ConfigurationFactory')->willReturn($configurationService);

        $serviceLocator->has('Phpro\AnnotatedForms\Form\Annotation\Builder')->willReturn(true);
        $serviceLocator->get('Phpro\AnnotatedForms\Form\Annotation\Builder')->willReturn($annotationBuilder);
        $annotationBuilder->setConfiguration(Argument::any())->willReturn(true);
        $annotationBuilder->getEventManager()->willReturn($eventManager);
        $annotationBuilder->setFormFactory($formFactory)->willReturn(true);

        $serviceLocator->has('Phpro\AnnotatedForms\Form\Factory')->willReturn(true);
        $serviceLocator->get('Phpro\AnnotatedForms\Form\Factory')->willReturn($formFactory);
    }

}
