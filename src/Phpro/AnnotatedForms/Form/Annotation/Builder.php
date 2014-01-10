<?php
namespace Phpro\AnnotatedForms\Form\Annotation;

use Phpro\AnnotatedForms\Options\Configuration;
use Phpro\AnnotatedForms\Options\ConfigurationAwareInterface;
use Phpro\AnnotatedForms\Event\FormEvent;
use Zend\Form\Annotation\AnnotationBuilder as ZendAnnotationBuilder;
use Zend\Form\Exception;

/**
 * Class Builder
 *
 * @package Phpro\AnnotatedForms
 */
class Builder extends ZendAnnotationBuilder
    implements ConfigurationAwareInterface
{

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return \Phpro\AnnotatedForms\Options\Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormSpecification($entity)
    {
        $config = $this->getConfiguration();
        $cache = $config->getCache();
        $cacheKey = $config->getCacheKey();

        // Pre event
        $event = new FormEvent(FormEvent::EVENT_FORM_SPECIFICATIONS_PRE, $this);
        $event->setParam('cacheKey', $cacheKey);
        $this->getEventManager()->trigger($event);

        // Load form specs:
        if ($config->isCacheable() && $cache->hasItem($cacheKey)) {
            $formSpec = $cache->getItem($cacheKey);
        } else {
            $formSpec = parent::getFormSpecification($entity);

            // Save cache:
            if ($config->isCacheable()) {
                try {
                    $cache->setItem($cacheKey, $formSpec);
                } catch (\Exception $e) {
                    // Silent fail
                }
            }
        }

        // post event
        $event = new FormEvent(FormEvent::EVENT_FORM_SPECIFICATIONS_POST, $this);
        $event->setParam('cacheKey', $cacheKey);
        $event->setParam('formSpec', $formSpec);
        $this->getEventManager()->trigger($event);

        return $formSpec;
    }

}