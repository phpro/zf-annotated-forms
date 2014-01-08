<?php

namespace PhproAnnotatedForms\Configuration;

use Zend\Cache\Storage\StorageInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * Class AnnotatedFormConfiguration
 *
 * @package PhproAnnotatedForms\Configuration
 */
class Configuration extends AbstractOptions
{

    /**
     * @var array
     */
    protected $initializers = array();

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var StorageInterface
     */
    protected $cache = null;

    /**
     * @var string
     */
    protected $className = '';

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
     * @param array $initializers
     */
    public function setInitializers($initializers)
    {
        $this->initializers = $initializers;
    }

    /**
     * @return array
     */
    public function getInitializers()
    {
        return $this->initializers;
    }

    /**
     * @param array $listeners
     */
    public function setListeners($listeners)
    {
        $this->listeners = $listeners;
    }

    /**
     * @return array
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

}