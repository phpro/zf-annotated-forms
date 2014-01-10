<?php

namespace spec\Phpro\AnnotatedForms\Options;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Options\Configuration');
    }

    public function it_should_extend_zend_abstract_options()
    {
        $this->shouldHaveType('Zend\Stdlib\AbstractOptions');
    }

    /**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     */
    public function it_should_have_cache_storage($cache)
    {
        $this->setCache($cache);
        $this->getCache()->shouldReturn($cache);
    }

    public function it_should_have_cache_key()
    {
        $cacheKey = 'cacheKey';
        $this->setCacheKey($cacheKey);
        $this->getCacheKey()->shouldReturn($cacheKey);
    }

    /**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     */
    public function it_should_know_if_it_is_cachable($cache)
    {
        $this->isCacheable()->shouldReturn(false);

        $this->setCache($cache);
        $this->setCacheKey('cache-key');
        $this->isCacheable()->shouldReturn(true);
    }

    public function it_should_have_entity()
    {
        $entity = 'entity';
        $this->setEntity($entity);
        $this->getEntity()->shouldReturn($entity);
    }

    /**
     * @param \Zend\EventManager\ListenerAggregateInterface $listener
     */
    public function it_should_have_multiple_listeners($listener)
    {
        $listeners = array($listener);
        $this->setListeners($listeners);
        $this->getListeners()->shouldReturn($listeners);
    }

    /**
     * @param \Zend\ServiceManager\InitializerInterface $initializer
     */
    public function it_should_have_multiple_initializers($initializer)
    {
        $initializers = array($initializer);
        $this->setInitializers($initializers);
        $this->getInitializers()->shouldReturn($initializers);
    }

}
