<?php

namespace spec\Phpro\AnnotatedForms\Service;

use Phpro\AnnotatedForms\Service\ConfigurationFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationFactorySpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Service\ConfigurationFactory');
    }

    public function it_should_implement_zend_factory_interface()
    {
        $this->shouldImplement('Zend\ServiceManager\FactoryInterface');
    }

    public function it_should_implement_zend_servicelocator_interface()
    {
        $this->shouldImplement('Zend\ServiceManager\ServiceLocatorAwareInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_have_a_service_locator($serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        $this->getServiceLocator()->shouldReturn($serviceLocator);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_configurate_itself_as_a_factory_service($serviceLocator)
    {
        $serviceLocator->get('Config')->willReturn(array(
            ConfigurationFactory::CONFIG_DEFAULT_NAMESPACE => array(
                ConfigurationFactory::CONFIG_DEFAULT_CONFIG_KEY => array(
                    'initializers' => array(),
                )
            )
        ));

        $this->createService($serviceLocator)->shouldReturn($this);
        $config = $this->getDefaults();

        $config->shouldBeArray();
        $config['initializers']->shouldBeArray();
    }

    public function it_should_have_default_configuration()
    {
        $defaultConfig = array();
        $this->setDefaults($defaultConfig);
        $this->getDefaults()->shouldReturn($defaultConfig);
    }

    public function it_should_create_configuration()
    {
        $this->setDefaults(array());
        $this->createConfiguration(array())->shouldBeAnInstanceOf('Phpro\AnnotatedForms\Options\Configuration');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \Zend\Cache\Storage\StorageInterface $cache
     */
    public function it_should_attach_cache_to_configuration($serviceLocator, $cache)
    {
        $this->mockServiceLocator($serviceLocator, $cache);
        $this->setDefaults(array());
        $configuration = $this->createConfiguration(array('cache' => 'cache'));
        $configuration->getCache()->shouldBe($cache);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \Zend\EventManager\ListenerAggregateInterface $listener
     */
    public function it_should_attach_listeners_to_configuration($serviceLocator, $listener)
    {
        $this->mockServiceLocator($serviceLocator, null, $listener);
        $this->setDefaults(array());
        $configuration = $this->createConfiguration(array('listeners' => array('listener')));
        $listeners = $configuration->getListeners();
        $listeners[0]->shouldBe($listener);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \Zend\ServiceManager\InitializerInterface $initializer
     */
    public function it_should_attach_initializers_to_configuration($serviceLocator, $initializer)
    {
        $this->mockServiceLocator($serviceLocator, null, null, $initializer);
        $this->setDefaults(array());
        $configuration = $this->createConfiguration(array('initializers' => array('initializers')));
        $initializers = $configuration->getListeners();
        $initializers[0]->shouldBe($initializer);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \Zend\Cache\Storage\StorageInterface $cache
     * @param \Zend\EventManager\ListenerAggregateInterface $listener
     * @param \Zend\ServiceManager\InitializerInterface $initializer
     */
    protected function mockServiceLocator($serviceLocator, $cache = null, $listener = null, $initializer = null)
    {
        if ($cache) {
            $serviceLocator->has('cache')->willReturn(true);
            $serviceLocator->get('cache')->willReturn($cache);
        }

        if ($listener) {
            $serviceLocator->has('listener')->willReturn(true);
            $serviceLocator->get('listener')->willReturn($listener);
        }

        if ($initializer) {
            $serviceLocator->has('initializer')->willReturn(true);
            $serviceLocator->get('initializer')->willReturn($initializer);
        }

        $this->setServiceLocator($serviceLocator);
    }

}
