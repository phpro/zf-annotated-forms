<?php

namespace Phpro\AnnotatedForms\Listener;

use Phpro\AnnotatedForms\Event\FormEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Form\Element\Collection;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Form\FormInterface;
use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class CreateCollectionInputFilterListener
 *
 * @package Application\Form\Listener
 */
class ConfigureCollectionInputFilterListener
    extends AbstractListenerAggregate
{

    const PRIORITY = 1000;

    /**
     * @var Form
     */
    protected $form;

    /**
     * {@inheritDocs}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(FormEvent::EVENT_FORM_CREATE_POST, array($this, 'configureCollectionInputFilter'), self::PRIORITY);
    }

    /**
     * @param FormEvent $e
     */
    public function configureCollectionInputFilter(FormEvent $e)
    {
        $form = $e->getTarget();
        if (!$form instanceof FormInterface) {
            return;
        }

        $this->form = $form;
        $this->handleCollections($form);
    }

    /**
     * @param Fieldset $fieldset
     */
    protected function handleCollections(Fieldset $fieldset)
    {
        foreach ($fieldset->getFieldsets() as $childFieldset) {

            // Configure collection:
            if ($childFieldset instanceof Collection) {
                $this->configureInputFilter($childFieldset);
            }

            // Search deeper:
            $this->handleCollections($childFieldset);
        }
    }

    /**
     * @param Collection $collection
     */
    protected function configureInputFilter(Collection $collection)
    {
        // Make it a collection input filter
        $inputFilter = new CollectionInputFilter();
        $inputFilter->setIsRequired(false);

        // Add the input filter of the target document as the real input filter:
        $targetElement = $collection->getTargetElement();
        if ($targetElement instanceof InputFilterProviderInterface) {
            $configuredFilter = $targetElement->getInputFilterSpecification();
            $inputFilter->setInputFilter($configuredFilter);
        }

        // Replace the current input filter in the actual form:
        $collectionName = $collection->getName();
        $formFilter = $this->form->getInputFilter();
        $formFilter->remove($collectionName);
        $formFilter->add($inputFilter, $collectionName);
    }

}
