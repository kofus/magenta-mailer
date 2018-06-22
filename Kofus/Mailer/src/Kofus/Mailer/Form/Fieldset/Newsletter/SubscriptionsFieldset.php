<?php
namespace Kofus\Mailer\Form\Fieldset\Newsletter;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class SubscriptionsFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
	public function init()
	{
	    
	    $session = new \Zend\Session\Container('Kofus_Mailer_Newsletter');
	    
		$el = new Element\MultiCheckbox('channels');
		$el->setValueOptions($session->voChannels);
		$this->add($el);
		
		$el = new Element\Submit('submit');
		$el->setLabel('Speichern');
		$this->add($el);
		
	}

	public function getInputFilterSpecification()
	{
	    $spec = array(
	        'channels' => array(
	            'required' => false
	        )
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
	
	protected function nodes()
	{
	    return $this->getServiceLocator()->get('KofusNodeService');
	}
	
}

