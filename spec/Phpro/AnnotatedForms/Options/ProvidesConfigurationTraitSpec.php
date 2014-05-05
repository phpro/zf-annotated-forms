<?php

namespace spec\Phpro\AnnotatedForms\Options;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

trait ProvidesConfigurationTraitSpec
{

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

}
