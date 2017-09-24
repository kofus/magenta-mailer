<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use InformationRetrieval\Db\Sqlite\SeleniumDb;
use Kofus\Media\Entity\PdfEntity;




class ConsoleController extends AbstractActionController
{
    
    public function sendBatchAction()
    {
        $news = $this->nodes()->getNode($this->params('news'), 'NS');
        if (! $news)
            throw new \Exception($this->params('news') . ' is not a valid node id for a news (NS)'); 
        $channel = $this->nodes()->getNode($this->params('channel'), 'NCH');
        if (! $channel)
            throw new \Exception($this->params('channel') . ' is not a valid node id for a channel (NCH)');
        
        
        $subscriptions = $this->nodes()->getRepository('SCP')->findBy(array('channel' => $channel));
        foreach ($subscriptions as $subscription) {
            $subscriberId = $subscription->getSubscriberId();
            $subscriber = $this->nodes()->getNode($subscriberId);
            if (! $subscriber instanceof \Kofus\Mailer\NewsSubscriberInterface)
                throw new \Exception($subscriber . ' must implement NewsSubscriberInterface');
            
            
            print $subscriber . ' ' . $subscriber->getEmailAddress() . PHP_EOL;
            $msg = $this->mailer()->createHtmlMessage($news->getContentHtml(), $subscriber->getMailerParams());
            $msg->addTo($subscriber->getEmailAddress());
            $msg->setSubject($news->getSubject());
            
            sleep(5);
            $this->mailer()->enqueue($msg);
        }
    }
    
   
    
}
