<?php
namespace Kofus\Mailer\Form\Fieldset\Template;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class MasterFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
	public function init()
	{
	    $config = $this->getServiceLocator()->get('KofusConfig');
	    
		$el = new Element\Text('title', array('label' => 'Administrative Title'));
		$this->add($el);
		
	    $el = new Element\Text('subject', array('label' => 'Subject'));
		$this->add($el);
		
		$el = new Element\Textarea('content_html', array('label' => 'Content'));
		$el->setAttribute('class', 'ckeditor');
		$this->add($el);
		
		$valueOptions = array();
		foreach ($config->get('mailer.layout') as $label => $path)
		    $valueOptions[$label] = $label;
		
		$el = new Element\Select('layout', array('label' => 'Layout'));
		$el->setValueOptions($valueOptions);
		$this->add($el);
	}

	public function getInputFilterSpecification()
	{
	    $spec = array(
	        'title' => array(
	        	'required' => true,
	        ),
	        'subject' => array(
	        	'required' => true,
	        ),
	        'content_html' => array(
	        	'required' => true
	        ),
	       'layout' => array('required' => true)
	    );
	    return $spec;
	}
	
	protected $sm;
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->sm = $serviceLocator;
	}
	
	public function getServiceLocator()
	{
		return $this->sm;
	}
	
}

