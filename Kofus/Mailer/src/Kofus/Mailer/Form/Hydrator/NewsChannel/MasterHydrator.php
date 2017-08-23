<?php
namespace Kofus\Mailer\Form\Hydrator\NewsChannel;

use Zend\Stdlib\Hydrator\HydratorInterface;


class MasterHydrator implements HydratorInterface
{
	public function extract($object)
	{
	    return array(
	    	'title' => $object->getTitle(),
	        'enabled' => $object->isEnabled()
	    );
	}

	public function hydrate(array $data, $object)
	{
        $object->setTitle($data['title']);
        $object->isEnabled($data['enabled']);
		return $object;
	}
}