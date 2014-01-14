<?php

namespace spec\Phpro\AnnotatedForms\Listener;

use Phpro\AnnotatedForms\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionElementListenerSpec extends ObjectBehavior
{
    /**
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     */
    public function let($annotationBuilder)
    {
        $this->setAnnotationBuilder($annotationBuilder);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Listener\CollectionElementListener');
    }

    public function it_should_extend_zend_abstractListenerAggregate()
    {
        $this->shouldHaveType('Zend\EventManager\AbstractListenerAggregate');
    }

    public function it_should_implement_factory_interface()
    {
        $this->shouldImplement('Zend\ServiceManager\FactoryInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     */
    public function it_should_be_able_to_configurate_itself($serviceLocator, $annotationBuilder)
    {
        $serviceLocator->get('Phpro\AnnotatedForms\Form\Annotation\Builder')->willReturn($annotationBuilder);
        $annotationBuilder->setConfiguration(Argument::any())->willReturn(true);

        $this->createService($serviceLocator)->shouldReturn($this);
    }

    /**
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     */
    public function it_should_have_annotation_builder($annotationBuilder)
    {
        $this->setAnnotationBuilder($annotationBuilder);
        $this->getAnnotationBuilder()->shouldReturn($annotationBuilder);
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     */
    public function it_should_attach_event_listeners($events, $annotationBuilder)
    {
        $this->attach($events);
        $annotationBuilder->setEventManager($events)->shouldHaveBeenCalled();
        $events->attach(FormEvent::EVENT_CONFIGURE_FIELDSET_PRE, Argument::cetera())->shouldHaveBeenCalled();
    }

    /**
     * @param \Phpro\AnnotatedForms\Event\FormEvent $event
     * @param \Phpro\AnnotatedForms\Form\Annotation\Builder $annotationBuilder
     * @param \Zend\Form\Element\Collection $collection
     * @param \ArrayObject $specs
     */
    public function it_should_load_fieldset_specs_from_annotation_builder($event, $annotationBuilder, $collection, $specs)
    {
        // Stub config
        $composedObject = 'composed-object';
        $specOptions = new \ArrayObject(array(
            'target_element' => array(
                'composedObject' => $composedObject,
            ),
        ));
        $specs->offsetGet('options')->willReturn($specOptions);

        // Stub methods
        $event->getTarget()->willReturn($collection);
        $event->getParam('spec')->willReturn($specs);
        $annotationBuilder->getFormSpecification($composedObject)->willReturn(array('elements' => array()));

        // Run spec test
        $this->configureCollection($event);


        // Todo: validate specs
        return;
        $result = $specs->offsetGet('options')->reveal();
        var_dump($result['target_element']['type']);exit;
        var_dump($specOptions['target_element']['type']);exit;
    }

}
