<?php
namespace Kofus\Mailer\Form\Fieldset\NewsSubscriber;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class MasterFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function init()
    {
        $el = new Element\Text('email', array(
            'label' => 'E-Mail'
        ));
        $this->add($el);
        
    }

    public function getInputFilterSpecification()
    {
        return array(
            'email' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'stringtrim'
                    ),
                    array('name' => 'stringtolower')
                ),
                'validators' => array(
                    array('name' => 'emailaddress')
                )
            )
        );
    }
}

