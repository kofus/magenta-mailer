<?php
namespace Kofus\Mailer\Form\Hydrator\Newsletter;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class AdministerHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $subscriptions = $this->nodes()->getRepository('SCP')->findBy(array('subscriberId' => $object->getNodeId()));
	    $channels = array();
	    foreach ($subscriptions as $subscription)
	        $channels[] = $subscription->getChannel()->getNodeId();
	    
	    return array(
	        'channels' => $channels
	    );
	}

	public function hydrate(array $data, $object)
	{
	    return $object;
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
	    
	protected function mailer()
	{
	    return $this->getServiceLocator()->get('KofusMailerService');
	}
	
	
}