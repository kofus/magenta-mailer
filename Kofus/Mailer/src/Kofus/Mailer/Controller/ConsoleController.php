<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use InformationRetrieval\Db\Sqlite\SeleniumDb;
use Kofus\Media\Entity\PdfEntity;




class ConsoleController extends AbstractActionController
{
    /**
     * First column must contain the email address
     * @throws \Exception
     */
    public function importSubscribersAction()
    {
        // Init
        $handle = fopen($this->params('filename'), 'r');
        if (! $handle)
            throw new \Exception('File could not be opened: ' . $this->params('filename'));
        
        $channel = $this->nodes()->getNode($this->params('channel'), 'NCH');
        if (! $channel)
            throw new \Exception('Channel not found: ' . $this->params('channel'));
        
        $validator = new \Zend\Validator\EmailAddress();
        $now = \DateTime::createFromFormat('U', REQUEST_TIME);
        
        $labels = fgetcsv($handle);
        $row = fgetcsv($handle);
        while ($row) {
            
            // Get email address
            $email = trim(strtolower($row[0]));
            if (! $validator->isValid($email))
                throw new \Exception('First column must contain a valid email address: ' . $email);

            // subscriber entity
            $isNew = false;
            $subscriber = $this->nodes()->getRepository('SCB')->findOneBy(array('emailAddress' => $email));
            if (! $subscriber) {
                $isNew = true;
                $subscriber = new \Kofus\Mailer\Entity\SubscriberEntity();
                $subscriber->setEmailAddress($email);
            }
            if (! $subscriber->getUriSegment())
                $subscriber->setUriSegment(\Zend\Math\Rand::getString(32, 'abcdefghijlkmnopqrstuvwxyz0123456789'));
            
            // Mailer params
            foreach ($row as $index => $value) {
                if ($index == 0) continue;
                $label = $labels[$index];
                
                switch ($label) {
                    default:
                        $subscriber->setMailerParam($label, $value);        
                }
            }
            
            // Name?
            $name = array();
            $index = array_search('firstname', $labels);
            if ($index) $name[] = $row[$index];
            $index = array_search('lastname', $labels);
            if ($index) $name[] = $row[$index];
            if ($name)
                $subscriber->setName(implode(' ', $name));

            // Persist subscriber
            $this->em()->persist($subscriber);
            
            // Subscription
            $subscription = $this->nodes()->getRepository('SCP')->findOneBy(array('subscriber' => $subscriber, 'channel' => $channel));
            if (! $subscription) {
                $subscription = new \Kofus\Mailer\Entity\SubscriptionEntity();
                $subscription->setSubscriber($subscriber)->setChannel($channel);
            }
            if (! $subscription->getTimestampActivation())
                $subscription->setTimestampActivation($now);
            $this->em()->persist($subscription);
            
            // Debug
            if ($isNew) {
                print 'NEW SUBSCRIBER ' . $subscriber . PHP_EOL;
            } else {
                print 'UPDATE SUBSCRIBER ' . $subscriber . PHP_EOL;
            }
            
            
            $row = fgetcsv($handle);
        }
        
        $this->em()->flush();
        
        print 'ALL DONE' . PHP_EOL;
    }
    
    
    
    
   
}
