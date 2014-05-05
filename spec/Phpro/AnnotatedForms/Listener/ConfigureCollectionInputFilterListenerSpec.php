<?php

namespace spec\Phpro\AnnotatedForms\Listener;

use Phpro\AnnotatedForms\Event\FormEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigureCollectionInputFilterListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Listener\ConfigureCollectionInputFilterListener');
    }

    public function it_should_extend_abstractListenerAggregate()
    {
        $this->shouldHaveType('Zend\EventManager\AbstractListenerAggregate');
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $eventManager
     */
    public function it_should_attach_a_listener_on_formCreate_post($eventManager)
    {
        $this->attach($eventManager);
        $eventManager->attach(FormEvent::EVENT_FORM_CREATE_POST, Argument::cetera())->shouldHaveBeenCalled();
    }

    /**
     * @param \Phpro\AnnotatedForms\Event\FormEvent $event
     * @param \Zend\Form\Form $form
     * @param \Zend\Form\Element\Collection $collection
     * @param \Zend\Form\InputFilterProviderFieldset $fieldset
     * @param \Zend\InputFilter\InputFilter $inputFilter
     */
    public function it_should_configure_collection_input_providers($event, $form, $collection, $fieldset, $inputFilter)
    {
        $event->getTarget()->willReturn($form);

        $form->getFieldsets()->willReturn(array($collection));
        $form->getInputFilter()->willReturn($inputFilter);

        $collection->getName()->willReturn('collection');
        $collection->getTargetElement()->willReturn($fieldset);
        $collection->getFieldsets()->willReturn(array($fieldset));

        $fieldset->getFieldsets()->willReturn(array());
        $fieldset->getInputFilterSpecification()->willReturn(array(
            'name' => array(
                'required' => true,
            ),
        ));

        $this->configureCollectionInputFilter($event);

        $inputFilter->remove('collection')->shouldHaveBeenCalled();
        $inputFilter->add(Argument::type('Zend\InputFilter\CollectionInputFilter'), 'collection')->shouldHaveBeenCalled();
    }

}
