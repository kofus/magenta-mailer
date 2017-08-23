<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class NewsChannelController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
    	$entities = $this->nodes()->getRepository('NCH')->findAll();
    	return new ViewModel(array(
    		'entities' => $entities
    	));
    }
    
    public function viewAction()
    {
        $this->archive()->uriStack()->push();
        $entity = $this->nodes()->getNode($this->params('id'), 'NEWSGROUP');
        //$subscriptions = $this->nodes()->getRepository('SUBSCR')->findBy(array('newsgroup' => $entity), array('timestampCreated' => 'DESC'));
        return new ViewModel(array(
        	'entity' => $entity,
          //  'subscriptions' => $subscriptions
        ));
    }
}
