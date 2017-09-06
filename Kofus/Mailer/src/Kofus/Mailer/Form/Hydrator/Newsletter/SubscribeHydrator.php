<?php
namespace Kofus\Mailer\Form\Hydrator\Newsletter;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class SubscribeHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    return array(
	    );
	}

	public function hydrate(array $data, $object)
	{
	    $subscriber = $this->nodes()->getRepository('SCB')->findOneBy(array('emailAddress' => $data['email']));
	    if (! $subscriber) {
	        $subscriber = $this->nodes()->createNode('SCB');
	        $subscriber->setEmailAddress($data['email']);
	    }
	    
	    $channels = array();
	    foreach ($data['channels'] as $channelId) {
	        $channel = $this->nodes()->getNode($channelId, 'NCH');
	        if ($channel)
	            $channels[] = $channel;
	    }
	    $this->mailer()->subscribe($subscriber, $channels);
	    
	    return $subscriber;
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