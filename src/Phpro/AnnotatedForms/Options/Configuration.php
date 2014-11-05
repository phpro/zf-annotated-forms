<?php

namespace Phpro\AnnotatedForms\Options;

use Zend\Cache\Storage\StorageInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * Class AnnotatedFormConfiguration
 *
 * @package Phpro\AnnotatedForms\Options
 */
class Configuration extends AbstractOptions
{

    /**
     * @var InitializerInterface[]
     */
    protected $initializers = array();

    /**
     * @var ListenerAggregateInterface[]
     */
    protected $listeners = array();

    /**
     * @var StorageInterface
     */
    protected $cache = null;

    /**
     * @var string
     */
    protected $cacheKey = '';

    /**
     * @var string
     */
    protected $entity = '';

    /**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param string $cacheKey
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * @return bool
     */
    public function isCacheable()
    {
        return $this->getCache() && $this->getCacheKey();
    }

    /**
     * @param mixed $entity
     *
     * @return bool
     */
    public function isCacheableEntity($entity)
    {
        $entity = is_object($entity) ? get_class($entity) : $entity;
        return $this->isCacheable() && $this->getEntity() === $entity;
    }

    /**
     * @param InitializerInterface[] $initializers
     */
    public function setInitializers($initializers)
    {
        $this->initializers = $initializers;
    }

    /**
     * @return InitializerInterface[]
     */
    public function getInitializers()
    {
        return $this->initializers;
    }

    /**
     * @param ListenerAggregateInterface[] $listeners
     */
    public function setListeners($listeners)
    {
        $this->listeners = $listeners;
    }

    /**
     * @return ListenerAggregateInterface[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

}
