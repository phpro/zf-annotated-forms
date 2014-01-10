<?php

namespace spec\Phpro\AnnotatedForms\Form\Annotation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class BuilderSpec extends ObjectBehavior
{

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function let($eventManager)
    {
        $eventManager->trigger(Argument::cetera())->willReturn(true);
        $eventManager->setIdentifiers(Argument::cetera())->willReturn(true);
        $eventManager->attach(Argument::cetera())->willReturn(true);
        $this->setEventManager($eventManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Form\Annotation\Builder');
    }

    public function it_should_extend_zend_annotationBuilder()
    {
        $this->shouldHaveType('Zend\Form\Annotation\AnnotationBuilder');
    }

    public function it_should_implement_configuration_aware_interface()
    {
        $this->shouldImplement('Phpro\AnnotatedForms\Options\ConfigurationAwareInterface');
    }

    /**
     * @param \Phpro\AnnotatedForms\Options\Configuration $configuration
     */
    public function it_should_have_configuration($configuration)
    {
        $this->setConfiguration($configuration);
        $this->getConfiguration()->shouldReturn($configuration);
    }

    public function it_should_trigger_event_before_retrieving_form_specifications()
    {

    }

    /**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     */
    public function it_should_load_form_from_cache($cache)
    {
        $cache->hasItem('cache-key')->willReturn(true);
        $cache->getItem('cache-key')->willReturn(array());
        $this->mockConfiguration($cache);

        $this->getFormSpecification('entity')->shouldBeArray();
    }


    public function it_should_parse_annotations()
    {
    }

    public function it_should_save_parsed_annotations_in_cache($cache)
    {

    }

    public function it_should_trigger_event_after_retrieving_form_specifications()
    {

    }


    /**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     * @param string $cacheKey
     *
     * @return \Phpro\AnnotatedForms\Options\Configuration
     */
    protected function mockConfiguration($cache = null, $cacheKey = 'cache-key')
    {
        $prophet = new Prophet();
        $configuration = $prophet->prophesize('\Phpro\AnnotatedForms\Options\Configuration');
        $configuration->getCache()->willReturn($cache);
        $configuration->getCacheKey()->willReturn($cacheKey);
        $configuration->isCacheable()->willReturn($cache && $cacheKey);

        $this->setConfiguration($configuration->reveal());
        return $configuration;
    }

}
