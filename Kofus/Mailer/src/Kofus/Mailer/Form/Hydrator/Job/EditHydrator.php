<?php
namespace Kofus\Mailer\Form\Hydrator\Job;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $channels = array();
	    foreach ($object->getChannels() as $channel)
	        $channels[] = $channel->getNodeId();
	    
	    
		return array(
		    'news' => $object->getNews()->getNodeId(),
		    'channels' => $channels
		);
	}

	public function hydrate(array $data, $object)
	{
	    $news = $this->nodes()->getNode($data['news'], 'NS');
	    foreach ($data['channels'] as $channelId)
	        $channels[] = $this->nodes()->getNode($channelId, 'NCH');
	    
	    $object->setNews($news);
	    $object->setChannels($channels);
	        
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