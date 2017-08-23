<?php
namespace Kofus\Mailer\Form\Fieldset\Newsletter;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class SubscribeFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
	public function init()
	{
	    $channels = $this->getServiceLocator()->get('KofusNodeService')->getRepository('NCH')->findBy(array('enabled' => true));
	    $valueOptions = array();
	    foreach ($channels as $channel)
	        $valueOptions[$channel->getNodeId()] = $channel->getTitle();
	    
		$el = new Element\MultiCheckbox('channels');
		$el->setValueOptions($valueOptions);
		$this->add($el);
		
		$el = new Element\Text('email');
		$el->setAttribute('placeholder', 'Ihre E-Mail-Adresse');
		$this->add($el);
		
		$el = new Element\Submit('submit');
		$el->setLabel('Jetzt abonnieren');
		$this->add($el);
		
	}

	public function getInputFilterSpecification()
	{
	    $spec = array(
	        'channels' => array(
	        	'required' => true,
	        ),
	        'email' => array(
	            'required' => true,
	            'filters' => array(
	                array('name' => 'stringtrim'),
	                array('name' => 'stringtolower'),
	            ),
	            'validators' => array(
	                array('name' => 'emailaddress')
	            )
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
	
}

