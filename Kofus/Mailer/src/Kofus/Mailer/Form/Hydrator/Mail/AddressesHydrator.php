<?php
namespace Kofus\Mailer\Form\Hydrator\Mail;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AddressesHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $channelIds = array();
	    
	    foreach ($object->getChannels() as $channel)
	        $channelIds[] = $channel->getNodeId();
	    
	    return array(
	        'channels' => $channelIds,
	        'from' => $object->getAddressFrom(),
	        'bcc' => $object->getAddressBcc()
	    );
		
		return $data;
	}

	public function hydrate(array $data, $object)
	{
	    $channels = array();
	    foreach ($data['channels'] as $channelId)
	        $channels[] = $this->nodes()->getNode($channelId, 'NCH');
	    
	    $object->setChannels($channels);
	    $object->setAddressFrom($data['from']);
	    $object->setAddressBcc($data['bcc']);
       	
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
	
}