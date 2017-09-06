<?php
namespace Kofus\Mailer\Form\Hydrator\Mail;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    return array();
	}

	public function hydrate(array $data, $object)
	{
	    $mailer = $this->getServiceLocator()->get('KofusMailerService');
	    
	    $msg = $mailer->createHtmlMessage($data['body']);
	    $msg->setSubject($data['subject']);
	    $msg->addTo($data['to']);
	    
	    
	    return $mailer->enqueue($msg, null, $object);
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
	
}