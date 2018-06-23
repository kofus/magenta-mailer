<?php
namespace Kofus\Mailer\Form\Hydrator\Mail;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class DispatchHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $dt = $object->getTimestampScheduled();
	    if (! $dt) {
	        $dt = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H') . ':00');
	        $dt->modify('+2 hour');
	    }
	    
	    return array(
	        'timestamp_scheduled' => $dt->format('Y-m-d H:i'),
	        'enabled' => $object->isEnabled()
	    );
		
		return $data;
	}

	public function hydrate(array $data, $object)
	{
	    $dt = null;
	    if (isset($data['timestamp_scheduled'])) {
	        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $data['timestamp_scheduled']);
	        $object->setTimestampSent(null);
	    }
	    
	    $object->isEnabled($data['enabled']);
	    $object->setTimestampScheduled($dt);
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