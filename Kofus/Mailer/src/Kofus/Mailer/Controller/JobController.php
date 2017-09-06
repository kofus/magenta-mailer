<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class JobController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
        
        $qb = $this->nodes()->createQueryBuilder('MJ');
        $paginator = $this->paginator($qb);
    	return new ViewModel(array(
    		'paginator' => $paginator
    	));
    }
    
    protected function refreshJobSubscribers($job)
    {
        $subscribers = array();
        foreach ($job->getChannels() as $channel) { 
            $subscriptions = $this->nodes()->getRepository('SCP')->findBy(array('channel' => $channel));
            foreach ($subscriptions as $subscription) {
                $subscriber = $subscription->getSubscriber();
                if (! isset($subscribers[$subscriber->getNodeId()]))
                    $subscribers[$subscriber->getNodeId()] = $subscriber;
            }
        }
        $job->setSubscribers($subscribers);
        $this->em()->persist($job);
        $this->em()->flush();
            
    }
    
    public function viewAction()
    {
        $this->archive()->uriStack()->push();
        $job = $this->nodes()->getNode($this->params('id'), 'MJ');
        
        if (! count($job->getSubscribers()))
            $this->refreshJobSubscribers($job);
        
        return new ViewModel(array(
            'entity' => $job
        ));
    }
    
    public function runAction()
    {
        $job = $this->nodes()->getNode($this->params('id'), 'MJ');
        return new ViewModel(array(
            
        ));
    }
    

}
