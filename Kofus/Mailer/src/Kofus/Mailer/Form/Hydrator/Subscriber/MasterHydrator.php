<?php
namespace Kofus\Mailer\Form\Hydrator\Subscriber;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class MasterHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    if ($object->getId()) {
    	    $subscriptions = $this->nodes()->createQueryBuilder('SCP')
    	       ->where('n.timestampActivation IS NOT NULL')
    	       ->andWhere('n.subscriber = :subscriber')
    	       ->setParameter('subscriber', $object)
    	       ->getQuery()->getResult();
	    } else {
	        $subscriptions = array();
	    }
	    
        $channels = array();
        foreach ($subscriptions as $subscription) {
            $channel = $subscription->getChannel();
            $channels[$channel->getNodeId()] = $channel->getNodeId();
        }
        
	    return array(
	    	'email' => $object->getEmailAddress(),
	        'name' => $object->getName(),
	        'channels' => $channels,
	        'uri_segment' => $object->getUriSegment(),
	        'tester' => $object->isTester()
	    );
	}

	public function hydrate(array $data, $object)
	{
	    $channelIds = array();
	    if (isset($data['channels']))
	        $channelIds = $data['channels'];
        	        
	    $subscriptions = $this->nodes()->getRepository('SCP')->findBy(array('subscriber' => $object));
	    $channels = $this->nodes()->getRepository('NCH')->findAll();
	    
	    foreach ($channels as $channel) {
	        $subscription = $this->nodes()->getRepository('SCP')->findOneBy(array('channel' => $channel, 'subscriber' => $object));
	        
	        if (in_array($channel->getNodeId(), $channelIds)) {
	            
	            // Add subscription
	            if (! $subscription) {
	                $subscription = new \Kofus\Mailer\Entity\SubscriptionEntity();
	                $subscription->setChannel($channel)->setSubscriber($object);
	            }
	            $subscription->setTimestampActivation(new \DateTime());
	            $this->em()->persist($subscription);
	            
	        } else {
	            // Delete subscription
	            if ($subscription) {
	                $this->em()->remove($subscription);
	            }
	        }
	    }
	    
	    $uriSegment = $data['uri_segment'];
	    if (! $data['uri_segment'])
	        $uriSegment = \Zend\Math\Rand::getString(32, 'abcdefghijklmnopqrstuvwxyz0123456789');
	    
	    $object->setUriSegment($uriSegment);
        $object->setEmailAddress($data['email']);
        $object->setName($data['name']);
        $object->isTester($data['tester']);
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
	
	protected function em()
	{
	    return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
	}
	
	
}