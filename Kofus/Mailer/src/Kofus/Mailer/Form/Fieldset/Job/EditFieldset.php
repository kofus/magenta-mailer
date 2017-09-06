<?php
namespace Kofus\Mailer\Form\Fieldset\Job;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class EditFieldset extends AddFieldset
{
	public function init()
	{
	    parent::init();
	    
	}

	public function getInputFilterSpecification()
	{
	    return  parent::getInputFilterSpecification();
	    $spec = array(
	        'news' => array(
	        	'required' => true,
	        ),
	        'channels' => array(
	        	'required' => true,
	        ),
	    );
	    return $spec;
	}
	
}

