<?php
namespace Kofus\Mailer\Form\Hydrator\Subscription;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{
	public function extract($object)
	{
	    $newsgroup = null;
	    if ($object->getNewsgroup())
	        $newsgroup = $object->getNewsgroup()->getNodeId();
	    
	    return array(
            'newsgroup' => $newsgroup,
	        'email' => $object->getEmailAddress(),
	        'gender' => $object->getGender(),
	        'title' => $object->getTitle(),
	        'first_name' => $object->getFirstName(),
	        'last_name' => $object->getLastName(),
	        'name' => $object->getName(),
	         
	    );
	}

	public function hydrate(array $data, $object)
	{
	    $newsgroup = null;
	    if ($data['newsgroup'])
	        $newsgroup = $this->nodes()->getNode($data['newsgroup'], 'NEWSGROUP');
	    
	    $object->setNewsgroup($newsgroup);
	    $object->setEmailAddress($data['email']);
	    if (! $object->getTimestampCreated())
	        $object->setTimestampCreated(new \DateTime());
	    $object->setName($data['name']);
	    $object->setGender($data['gender']);
	    $object->setTitle($data['title']);
	    $object->setFirstName($data['first_name']);
	    $object->setLastName($data['last_name']);
	    
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