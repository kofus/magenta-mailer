<?php
namespace Kofus\Mailer\Form\Hydrator\NewsSubscriber;

use Zend\Stdlib\Hydrator\HydratorInterface;


class MasterHydrator implements HydratorInterface
{
	public function extract($object)
	{
	    return array(
	    	'email' => $object->getEmailAddress(),
	    );
	}

	public function hydrate(array $data, $object)
	{
        $object->setEmailAddress($data['email']);
		return $object;
	}
}