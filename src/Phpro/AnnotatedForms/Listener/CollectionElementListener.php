<?php

namespace Phpro\AnnotatedForms\Listener;


use Phpro\AnnotatedForms\Event\FormEvent;
use Phpro\AnnotatedForms\Form\Annotation\Builder;
use Phpro\AnnotatedForms\Options\Configuration;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\Element\Collection;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CollectionElementListener
 *
 * @package Phpro\AnnotatedForms\Listener
 */
class CollectionElementListener
    extends  AbstractListenerAggregate
    implements FactoryInterface
{

    const PRIORITY = 1000;

    /**
     * @var Builder
     */
    protected $annotationbuilder;

    /**
     * {@inheritDocs}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->getAnnotationbuilder()->setEventManager($events);
        $this->listeners[] = $events->attach(FormEvent::EVENT_CONFIGURE_FIELDSET_PRE, array($this, 'configureCollection'), self::PRIORITY);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Builder $annotationBuilder */
        $annotationBuilder = $serviceLocator->get('Phpro\AnnotatedForms\Form\Annotation\Builder');
        $annotationBuilder->setConfiguration(new Configuration());
        $this->setAnnotationbuilder($annotationBuilder);

        return $this;
    }

    /**
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationbuilder
     */
    public function setAnnotationbuilder($annotationbuilder)
    {
        $this->annotationbuilder = $annotationbuilder;
    }

    /**
     * @return \Phpro\AnnotatedForms\Form\Annotation\Builder
     */
    public function getAnnotationbuilder()
    {
        return $this->annotationbuilder;
    }

    /**
     * @param FormEvent $event
     */
    public function configureCollection(FormEvent $event)
    {
        if (!$event->getTarget() instanceof Collection) {
            return;
        }

        // Load collection specs
        $specs = $event->getParam('spec');
        if (!isset($specs['options']['target_element']['composedObject'])) {
            return;
        }
        $composedObject = $specs['options']['target_element']['composedObject'];


        // Load composed object specs as fieldset:
        $specs = $event->getParam('spec');
        $annotationBuilder = $this->getAnnotationbuilder();
        $formSpecs = $annotationBuilder->getFormSpecification($composedObject);
        $formSpecs['type'] = 'Zend\Form\Fieldset';
        $formSpecs['object'] = $composedObject;

        // Add form specs to fieldset specs
        foreach ($formSpecs as $key => $value) {
            $specs['options']['target_element'][$key] = $value;
        }
    }

}
