<?php
namespace Phpro\AnnotatedForms\Form\Annotation;

use Phpro\AnnotatedForms\Options\Configuration;
use Phpro\AnnotatedForms\Options\ConfigurationAwareInterface;
use Phpro\AnnotatedForms\Event\FormEvent;
use Zend\Form\Annotation\AnnotationBuilder as ZendAnnotationBuilder;
use Zend\Form\Exception;
use Zend\Stdlib\ArrayUtils;

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
     * @param object|string $entity
     *
     * @return void|\Zend\Form\Form
     */
    public function createForm($entity)
    {
        $this->triggerEvent(FormEvent::EVENT_FORM_CREATE_PRE, $this, array('entity' => $entity));
        $form = parent::createForm($entity);
        $this->triggerEvent(FormEvent::EVENT_FORM_CREATE_POST, $form, array('entity' => $entity));

        return $form;
    }

    /**
     * Note:
     * Only save to / load from cache if it is the specified entity.
     * Otherwise strange stuff will happen when using fieldsets and collections.
     *
     * {@inheritDoc}
     */
    public function getFormSpecification($entity)
    {
        $config = $this->getConfiguration();
        $cache = $config->getCache();
        $cacheKey = $config->getCacheKey();

        // Pre event
        $this->triggerEvent(FormEvent::EVENT_FORM_SPECIFICATIONS_PRE, $this, array(
            'cacheKey' => $cacheKey,
        ));

        // Load form specs from cache:
        if ($config->isCacheableEntity($entity) && $cache->hasItem($cacheKey)) {
            $formSpec = $cache->getItem($cacheKey);

        // Parse form specs:
        } else {
            $formSpec = parent::getFormSpecification($entity);

            // Save cache:
            if ($config->isCacheableEntity($entity)) {
                try {
                    $cache->setItem($cacheKey, $formSpec);
                } catch (\Exception $e) {
                    // Silent fail
                }
            }
        }

        // Post event
        $this->triggerEvent(FormEvent::EVENT_FORM_SPECIFICATIONS_POST, $this, array(
                'cacheKey' => $cacheKey,
                'formSpec' => $formSpec,
            )
        );

        return $formSpec;
    }

    /**
     * @param       $name
     * @param       $subject
     * @param array $params
     */
    protected function triggerEvent($name, $subject, $params = array())
    {
        $event = FormEvent::create($name, $subject, $this->getConfiguration(), $params);
        $this->getEventManager()->trigger($event);
    }

}
