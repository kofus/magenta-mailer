<?php
namespace Kofus\Mailer\Form\Fieldset\Newsgroup;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class SubscribeFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function init()
	{
		$el = new Element\MultiCheckbox('channels', array('label' => 'Newsletter'));
		$el->setValueOption(array('Publikation neuer Artikel', 'Publikation neuer Kataloge / Zeitschriften'));
		$this->add($el);
		
	}

	public function getInputFilterSpecification()
	{
	    $spec = array(
	        'channels' => array(
	        	'required' => true,
	        ),
	    );
	    return $spec;
	}
}

