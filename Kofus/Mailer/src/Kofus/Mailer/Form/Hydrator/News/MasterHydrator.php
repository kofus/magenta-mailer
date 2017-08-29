<?php
namespace Kofus\Mailer\Form\Hydrator\News;

use Zend\Stdlib\Hydrator\HydratorInterface;

class MasterHydrator implements HydratorInterface
{
	public function extract($object)
	{
		$data['subject'] = $object->getSubject();
		$data['content_html'] = $object->getContentHtml();
		$data['content_text'] = $object->getContentText();
		$data['template'] = $object->getTemplate();
		
		return $data;
	}

	public function hydrate(array $data, $object)
	{
	    $object->setSubject($data['subject']);
       	$object->setContentHtml($data['content_html']);
       	$object->setContentText($data['content_text']);
       	$object->setTemplate($data['template']);
       	
		return $object;
	}
}