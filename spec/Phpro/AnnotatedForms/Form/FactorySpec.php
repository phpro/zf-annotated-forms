<?php

namespace spec\Phpro\AnnotatedForms\Form;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\AnnotatedForms\Form\Factory');
    }

    public function it_should_extend_zend_form_factory()
    {
        $this->shouldHaveType('Zend\Form\Factory');
    }
}
