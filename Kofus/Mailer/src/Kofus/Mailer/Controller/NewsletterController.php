<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class NewsletterController extends AbstractActionController
{
    public function optInAction()
    {
        $activationToken = $this->params('id');
        $subscriptions = $this->mailer()->optIn($activationToken);
        return new ViewModel(array(
            'subscriptions' => $subscriptions
        ));
    }
    
    public function editAction()
    {
        $token = $this->params('id');
        $subscriber = $this->nodes()->getRepository('SCB')->findOneBy(array('token' => $token));
        $allChannels = $this->getServiceLocator()->get('KofusNodeService')->getRepository('NCH')->findAll();
        
        $channels = array();
        foreach ($allChannels as $channel) {
            if ($channel->isEnabled()) {
                $channels[] = $channel;
                continue;
            }
            $subscription = $this->nodes()->getRepository('SCP')->findOneBy(array('subscriberId' => $subscriber->getNodeId(), 'channel' => $channel));
            if ($subscription) {
                $channels[] = $channel; continue;
            }
        }
        $fieldset = new \Kofus\Mailer\Form\Fieldset\Newsletter\AdministerFieldset();
        $fieldset->setChannels($channels);
        
        $form = $this->fb()->setConfig(array(
                'sections' =>
                    array('administer' => 
                            array(
                                'fieldset' => $fieldset,
                                'hydrator' => 'Kofus\Mailer\Form\Hydrator\Newsletter\AdministerHydrator'
                            )
                    ),             
                    'element_options' => array(
                        'column-size' => 'sm-12',
                        'label_attributes' => array(
                            'class' => 'col-sm-12'
                        )
                    )
                )
            )
        ->setObject($subscriber)
        ->buildForm();
        
        $msgs = array();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $subscriptions = array();
                foreach ($this->nodes()->getRepository('SCP')->findBy(array('subscriberId' => $subscriber->getNodeId())) as $subscription) {
                    $subscriptions[$subscription->getChannel()->getNodeId()] = $subscription;                    
                }
                
                $channelIds = array();
                if (isset($data['administer']['channels']))
                    $channelIds = $form->get('administer')->get('channels')->getValue();
                foreach ($allChannels as $channel) {
                    $channelId = $channel->getNodeId();
                    if (isset($subscriptions[$channelId]) && ! in_array($channelId, $channelIds)) {
                        $msgs[] = 'Sie erhalten ab sofort keine weiteren Benachrichtigungen zu "' . $channel->getTitle() . '"';
                        $this->em()->remove($subscriptions[$channelId]);
                    } elseif (! isset($subscriptions[$channelId]) && in_array($channelId, $channelIds)) {
                        $msgs[] = 'Sie erhalten ab sofort Benachrichtungen zu "' . $channel->getTitle() . '"';
                        $subscription = new \Kofus\Mailer\Entity\NewsSubscriptionEntity();
                        $subscription->setChannel($channel)
                            ->setStatus('active')
                            ->setSubscriberId($subscriber->getNodeId());
                        $this->em()->persist($subscription);
                    }
                }
                $this->em()->flush();
            }
        }
        
        return new ViewModel(array(
            'subscriber' => $subscriber,
            'form' => $form,
            'msgs' => $msgs
        ));
        
        
    }
}
