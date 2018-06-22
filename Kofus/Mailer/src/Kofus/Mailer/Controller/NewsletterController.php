<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class NewsletterController extends AbstractActionController
{
    public function optInAction()
    {
        $subscriber = $this->nodes()->getRepository('SCB')->findOneBy(array('uriSegment' => $this->params('id')));
        $session = new \Zend\Session\Container('Kofus_Mailer_Newsletter');
        
        $subscriptions = $this->nodes()->createQueryBuilder('SCP')
            ->where('n.subscriber = :subscriber')
            ->setParameter('subscriber', $subscriber)
            ->getQuery()->getResult();
        
        $publicChannels = $this->nodes()->getRepository('NCH')->findBy(array('enabled' => true));
        
        $voChannels = array();
        foreach ($subscriptions as $subscription) {
            $channel = $subscription->getChannel();
            $voChannels[$channel->getNodeId()] = $channel->getTitle();
        }
        foreach ($publicChannels as $channel) {
            $voChannels[$channel->getNodeId()] = $channel->getTitle();
        }
        $session->voChannels = $voChannels;
        
        $form = $this->fb()
            ->setConfig(array('sections' =>
                array('subscribe' => array(
                    'fieldset' => 'Kofus\Mailer\Form\Fieldset\Newsletter\SubscriptionsFieldset',
                    'hydrator' => 'Kofus\Mailer\Form\Hydrator\Newsletter\SubscriptionsHydrator'
                )),             'element_options' => array(
                    'column-size' => 'sm-12',
                    'label_attributes' => array(
                        'class' => 'col-sm-12'
                    )
                )
            ))
            ->setObject($subscriber)
            ->buildForm('formNewsletterSubscribe');
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $this->em()->persist($subscriber);
                $this->em()->flush();
                $this->flashMessenger()->addSuccessMessage('Vielen Dank! Ihre Einstellungen wurden gespeichert.');
                return $this->redirect()->toUrl('/');
            }
        }
        
        return new ViewModel(array(
            'subscriber' => $subscriber,
            'form' => $form
        ));
    }
    
    public function editAction()
    {
        return $this->forward()->dispatch('Kofus\Mailer\Controller\Newsletter', array('action' => 'opt-in', 'id' => $this->params('id')));     
        
    }
}
