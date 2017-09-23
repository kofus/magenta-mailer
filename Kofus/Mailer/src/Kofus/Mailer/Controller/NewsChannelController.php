<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class NewsChannelController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
        
        $qb = $this->nodes()->createQueryBuilder('NCH');
        $paginator = $this->paginator($qb);
    	return new ViewModel(array(
    		'paginator' => $paginator
    	));
    }
    
    public function viewAction()
    {
        $this->archive()->uriStack()->push();
        
        $node = $this->nodes()->getNode($this->params('id'), 'NCH');
        $subscriptions = $this->nodes()->getRepository('SCP')->findBy(array('channel' => $node));
        
        return new ViewModel(array(
            'entity' => $node,
            'subscriptions' => $subscriptions
        ));
    }
}
