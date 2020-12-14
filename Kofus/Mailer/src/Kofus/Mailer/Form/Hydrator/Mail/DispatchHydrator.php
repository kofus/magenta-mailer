<?php
namespace Kofus\Mailer\Form\Hydrator\Mail;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class DispatchHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $dt = array('year' => null, 'month' => null, 'day' => null, 'hour' => null, 'minute' => null);
	    if ($object->getTimestampScheduled()) $dt =  $object->getTimestampScheduled()->format('Y-m-d H:i');
	    
	    return array(
	        'timestamp_scheduled' => $dt,
	        'enabled' => $object->isEnabled()
	    );
		
	}

	public function hydrate(array $data, $object)
	{
	    $dt = null;
	    $t = $data['timestamp_scheduled'];
	    
	    if ($t['day'] && $t['month'] && $t['year']) {
	        $dt = \DateTime::createFromFormat('Y-m-d H:i', $t['year'] . '-' . $t['month'] . '-' . $t['day'] . ' ' . $t['hour'] . ':' . $t['minute']);
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