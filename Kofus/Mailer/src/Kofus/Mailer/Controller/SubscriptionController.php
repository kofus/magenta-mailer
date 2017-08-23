<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class SubscriptionController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
    	$qb = $this->nodes()->createQueryBuilder('SUBSCR');
    	$paginator = $this->paginator($qb);
    	
    	return new ViewModel(array(
    		'paginator' => $paginator
    	));
    }
}
