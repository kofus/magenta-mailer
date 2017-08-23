<?php
namespace Kofus\Mailer\Form\Fieldset\Newsgroup;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class MasterFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function init()
	{
		$el = new Element\Text('title', array('label' => 'Name'));
		$this->add($el);
		
	}

	public function getInputFilterSpecification()
	{
	    $spec = array(
	        'title' => array(
	        	'required' => true,
	        ),
	    );
	    return $spec;
	}
}

