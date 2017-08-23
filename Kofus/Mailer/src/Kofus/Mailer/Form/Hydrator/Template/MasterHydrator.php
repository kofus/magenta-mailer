<?php
namespace Kofus\Mailer\Form\Hydrator\Template;

use Zend\Stdlib\Hydrator\HydratorInterface;

class MasterHydrator implements HydratorInterface
{
	public function extract($object)
	{
	    $data['title'] = $object->getTitle();
		$data['subject'] = $object->getSubject();
		$data['content_html'] = $object->getContentHtml();
		$data['layout'] = $object->getLayout();
		
		return $data;
	}

	public function hydrate(array $data, $object)
	{
        $object->setTitle($data['title']);
	    $object->setSubject($data['subject']);
       	$object->setContentHtml($data['content_html']);
       	$object->setLayout($data['layout']);
       	
		return $object;
	}
}