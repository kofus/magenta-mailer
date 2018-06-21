<?php
namespace Kofus\Mailer\Form\Fieldset\Channel;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class MasterFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function init()
    {
        $el = new Element\Text('title', array(
            'label' => 'Name'
        ));
        $this->add($el);
        
        $el = new Element\Checkbox('enabled', array('label' => 'public?'));
        $this->add($el);
    }

    public function getInputFilterSpecification()
    {
        return array(
            'title' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'stringtrim'
                    )
                )
            )
        );
    }
}

