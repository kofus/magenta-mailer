<?php
namespace Kofus\Mailer\Form\Fieldset\Mail;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class MasterFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
	public function init()
	{
	    $config = $this->getServiceLocator()->get('KofusConfigService');
	    
	    $el = new Element\Text('to', array('label' => 'To'));
	    $this->add($el);
	    
	    $el = new Element\Text('subject', array('label' => 'Betreff'));
		$this->add($el);
		
		$el = new Element\Textarea('body', array('label' => 'Inhalt (HTML)'));
		$el->setAttribute('class', 'ckeditor');
		$this->add($el);
		
		$el = new Element\Textarea('body_text', array('label' => 'Inhalt (Text)'));
		$this->add($el);
		
		
		$valueOptions = array();
		foreach ($config->get('mailer.templates.enabled', array()) as $template)
		    $valueOptions[$template] = $template;
		
		$el = new Element\Select('template', array('label' => 'Template'));
		$el->setValueOptions($valueOptions);
		$this->add($el);
	}

	public function getInputFilterSpecification()
	{
	    $spec = array(
	        'to' => array(
	            'required' => true,
	            'filters' => array(
	                array('name' => 'Zend\Filter\StringTrim'),
	                array('name' => 'Zend\Filter\StringToLower')
	            ),
	            'validators' => array(
	                array('name' => 'Zend\Validator\EmailAddress')
	            )
	        ),
	        'subject' => array(
	        	'required' => true,
	            'filters' => array(
	                array('name' => 'Zend\Filter\StringTrim')
	            )
	        ),
	        'body' => array(
	        	'required' => true,
	        ),
	        'body_text' => array(
	            'required' => false,
	            'filters' => array(
	                array('name' => 'Zend\Filter\StringTrim')
	            )
	            
	        ),
	       'template' => array('required' => true)
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

