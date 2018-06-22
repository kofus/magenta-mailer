<?php
namespace Kofus\Mailer\Form\Hydrator\Newsletter;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class SubscriptionsHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $subscriber = $object;
	    
        $channels = array();
        foreach ($this->getSubscriptions($subscriber) as $subscription) {
            $channels[] = $subscription->getChannel()->getNodeId();
        }
	    
	    return array(
	        'channels' => $channels
	    );
	}

	public function hydrate(array $data, $object)
	{
	    $subscriber = $object;
	    
	    $now = \DateTime::createFromFormat('U', REQUEST_TIME);
	    foreach ($this->getSubscriptions($subscriber) as $subscription) {
	        $channel = $subscription->getChannel();
	        if (in_array($channel->getNodeId(), $data['channels'])) {
	            if (! $subscription->getTimestampActivation()) {
	                $subscription->setTimestampActivation($now);
	                $this->em()->persist($subscription);
	            }
	            
	        } else {
	            $this->em()->remove($subscription);
	        }
	    }
	    
	    foreach ($data['channels'] as $channelId) {
	        $channel = $this->nodes()->getNode($channelId, 'NCH');
	        $subscription = $this->nodes()->getRepository('SCP')->findOneBy(array('channel' => $channel, 'subscriber' => $subscriber));
	        if (! $subscription) {
	            $subscription = new \Kofus\Mailer\Entity\SubscriptionEntity();
	            $subscription->setChannel($channel);
	            $subscription->setSubscriber($subscriber);
	            $subscription->setTimestampActivation($now);
	            $this->em()->persist($subscription);
	        }
	    }
	    
	    
	    return $object;
	}
	
	protected function getSubscriptions($subscriber)
	{
	    return $this->nodes()->createQueryBuilder('SCP')
    	    ->where('n.subscriber = :subscriber')
    	    ->setParameter('subscriber', $subscriber)
    	    ->getQuery()->getResult();
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
	
	protected function em()
	{
	    return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
	}
	
	
}