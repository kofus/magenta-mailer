<?php
namespace Kofus\Mailer\Form\Hydrator\Job;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
		return array(
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