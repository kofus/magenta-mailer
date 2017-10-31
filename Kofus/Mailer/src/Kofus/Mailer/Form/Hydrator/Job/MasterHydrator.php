<?php
namespace Kofus\Mailer\Form\Hydrator\Job;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $newsId = null;
	    if ($object->getNews())
	        $newsId = $object->getNews()->getNodeId();
	    
	    $channels = array();
	    foreach ($object->getChannels() as $channel)
	        $channels[] = $channel->getNodeId();
	    
		return array(
		    'news' => $newsId,
		    'channels' => $channels,
		    'scheduled' => new \DateTime(),
		    'enabled' => $object->isEnabled(),
		    'from' => $object->getParam('From')
		);
	}

	public function hydrate(array $data, $object)
	{
	    $news = $this->nodes()->getNode($data['news'], 'NS');
	    foreach ($data['channels'] as $channelId)
	        $channels[] = $this->nodes()->getNode($channelId, 'NCH');
	    
	    $object->setNews($news);
	    $object->setChannels($channels);

	    $scheduled = \DateTime::createFromFormat('Y-m-d H:i:s', $data['scheduled']);
	    $object->setTimestampScheduled($scheduled);
	    $object->isEnabled($data['enabled']);
	    
	    if (isset($data['from']))
	        $object->setParam('From', $data['from']);
	        
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