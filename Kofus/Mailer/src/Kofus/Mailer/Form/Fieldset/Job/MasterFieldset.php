<?php
namespace Kofus\Mailer\Form\Fieldset\Job;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Kofus\System\Form\Element\NodeSelect;



class MasterFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
	public function init()
	{
	    
	    $el = new NodeSelect('news', array('label' => 'News', 'node-type' => 'NS'));
	    $this->add($el);
	    
		$channels = array();
		foreach ($this->nodes()->getRepository('NCH')->findAll() as $channel)
		    $channels[$channel->getNodeId()] = $channel->getTitle();
		$el = new Element\MultiCheckbox('channels', array('label' => 'Channels'));
		$el->setValueOptions($channels);
		$this->add($el);
		
		$el = new Element\DateTimeSelect('scheduled', array('label' => 'Geplanter Versandbeginn'));
		$this->add($el);
		
		$config = $this->getServiceLocator()->get('KofusConfig');
		if ($config->get('mailer.addresses')) {
		    $valueOptions = array();
		    foreach ($config->get('mailer.addresses') as $address)
		        $valueOptions[$address->toString()] = $address->toString();
    		$el = new Element\Select('from', array(
    		    'label' => 'Absender',
    		    'value_options' => $valueOptions
    		));
    		$this->add($el);
		}
		
		$el = new Element\Checkbox('enabled', array('label' => 'enabled?'));
		$this->add($el);
		
		
	}

	public function getInputFilterSpecification()
	{
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

