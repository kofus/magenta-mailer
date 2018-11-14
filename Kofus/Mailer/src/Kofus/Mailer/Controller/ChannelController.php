<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class ChannelController extends AbstractActionController
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
        $qb = $this->nodes()->createQueryBuilder('SCP')
            ->where('n.channel = :channel')
            ->setParameter('channel', $node)
            ->orderBy('n.timestampActivation', 'DESC');
        
        return new ViewModel(array(
            'entity' => $node,
            'subscriptions' => $this->paginator($qb)
        ));
    }
    
    public function truncateAction()
    {
        $node = $this->nodes()->getNode($this->params('id'), 'NCH');
        $subscriptions = $this->nodes()->createQueryBuilder('SCP')
            ->where('n.channel = :channel')
            ->setParameter('channel', $node)
            ->getQuery()->getResult();
        
        $sessionId = $this->getServiceLocator()->get('Zend\Session\SessionManager')->getId();
        $token = md5($sessionId . $node->getNodeId());
        
        if ($this->params()->fromQuery('confirm') == $token) {
            foreach ($subscriptions as $subscription) {
                $this->em()->remove($subscription);
            }
            $this->em()->flush();
            $this->flashMessenger()->addSuccessMessage('Abonnements gelöscht');
            return $this->redirect()->toUrl($this->archive()->uriStack()->pop());
        }
        
        return new ViewModel(array(
            'channel' => $node,
            'subscriptions' => $subscriptions,
            'token' => $token
        ));
    }
    
    public function csvAction()
    {
        $node = $this->nodes()->getNode($this->params('id'), 'NCH');
        $subscriptions = $this->nodes()->createQueryBuilder('SCP')
            ->where('n.channel = :channel')
            ->setParameter('channel', $node)
            ->getQuery()->getResult();
        

        foreach ($subscriptions as $subscription) {
            $subscriber = $subscription->getSubscriber();
            print $subscriber->getName() . ' (' . $subscriber->getNodeId() . ') ';
            print htmlentities('<' . $subscriber->getEmailAddress() . '>') . '<br>';
        }
        
        exit();
    }
}
