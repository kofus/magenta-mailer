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
    
    public function batchImportAction()
    {
        $dt = new \DateTime();
        $channel = $this->nodes()->getNode($this->params('id'), 'NCH');
        $stream = file_get_contents('data/emails.txt');
        foreach (explode("\n", $stream) as $line) {
            $email = trim(strtolower($line));
            if ($email) {
                print $email . ' ';
                $subscriber = $this->nodes()->getRepository('SCB')->findOneBy(array('emailAddress' => $email));
                if (! $subscriber) {
                    $subscriber = $this->nodes()->createNode('SCB');
                    $subscriber->setEmailAddress($email);
                    $this->em()->persist($subscriber);
                    $this->em()->flush();
                }
                print $subscriber->getNodeId() . ' ';
                
                $subscription = $this->nodes()->getRepository('SCP')->findOneBy(array('subscriberId' => $subscriber->getNodeId(), 'channel' => $channel));
                if (! $subscription) {
                    $subscription = new \Kofus\Mailer\Entity\NewsSubscriptionEntity();
                    $subscription->setChannel($channel)
                        ->setSubscriberId($subscriber->getNodeId())
                        ->setTimestampActivationSubscriber($dt);
                    $this->em()->persist($subscription);
                    $this->em()->flush();
                }
                print $subscription->getNodeId() . ' ';
                
                print '<br>';
            }
        }
        die('DONE');
    }
}
