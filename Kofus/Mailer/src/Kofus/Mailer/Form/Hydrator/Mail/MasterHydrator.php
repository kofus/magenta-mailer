<?php
namespace Kofus\Mailer\Form\Hydrator\Mail;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class MasterHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    return array(
	        'subject' => $object->getSubject(),
	        'content_html' => $object->getContentHtml(),
	        'template' => $object->getTemplate(),
	        'system_id' => $object->getSystemId()
	    );
		
		return $data;
	}

	public function hydrate(array $data, $object)
	{
	    $object->setSubject($data['subject']);
       	$object->setContentHtml($data['content_html']);
       	$object->setTemplate($data['template']);
       	$object->setSystemId($data['system_id']);
       	
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