<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class NewsSubscriberController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
        
        $qb = $this->nodes()->createQueryBuilder('SCB');
        $paginator = $this->paginator($qb);
    	return new ViewModel(array(
    		'paginator' => $paginator
    	));
    }
    
    public function viewAction()
    {
        $this->archive()->uriStack()->push();
        $subscriber = $this->nodes()->getNode($this->params('id'), 'SCB');
        $subscriptions = $this->nodes()->getRepository('SCP')->findBy(array('subscriberId' => $subscriber->getId()));
        
        return new ViewModel(array(
            'subscriber' =>  $subscriber,
            'subscriptions' => $subscriptions
        ));
    }
}
