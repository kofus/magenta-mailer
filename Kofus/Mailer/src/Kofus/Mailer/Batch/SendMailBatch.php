<?php
namespace Kofus\Mailer\Batch;
use Kofus\System\Batch\AbstractBatch;

class SendMailBatch extends AbstractBatch
{
    public function run()
    {
        date_default_timezone_set('Europe/Berlin');
        $now = \DateTime::createFromFormat('U', REQUEST_TIME);
        
        $mails = $this->nodes()->createQueryBuilder('ML')
            ->where('n.enabled = true')
            ->andWhere('n.timestampSent IS NULL')
            ->andWhere('n.timestampScheduled <= :date')
            ->setParameter('date', new \DateTime())
            ->getQuery()->getResult();
        
        foreach ($mails as $mail) {
            print 'NACHRICHT: ' . $mail . PHP_EOL;
            
            $channels = $mail->getChannels();
            
            foreach ($channels as $channel) {
                print 'KANAL: ' . $channel . PHP_EOL . PHP_EOL;
                $subscriptions = $this->nodes()->createQueryBuilder('SCP')
                    ->leftJoin('Kofus\Mailer\Entity\SubscriberEntity', 's', 'WITH', 'n.subscriber = s')
                    ->where('n.channel = :channel')
                    ->setParameter('channel', $channel)
                    ->orderBy('s.tester', 'DESC')
                    ->getQuery()->getResult();
                
                foreach ($subscriptions as $subscription) {
                    $subscriber = $subscription->getSubscriber();
                    print '- ' . $subscriber->getEmailAddress();
                    if ($subscriber->getName())
                        print ' (' . $subscriber->getName() . ')';
                    print PHP_EOL;
                    
                    $viewParams = array(
                        'content' => $mail->getContentHtml(),
                        'subscriber' => $subscriber
                    );
                    $msg = $this->mailer()->createHtmlMessage($viewParams, $subscriber->getMailerParams(), $mail->getTemplate());
                    $msg->addTo($subscriber->getEmailAddress());
                    $msg->addBcc('log@kofus.de');
                    $msg->setSubject($mail->getSubject());
                    
                    sleep(5);
                    $this->mailer()->send($msg);
                }
            }
            
            $mail->setTimestampSent($now);
            $mail->setTimestampScheduled(null);
            $mail->isEnabled(false);
            $this->em()->persist($mail);
        }
        $this->em()->flush();
        
        print 'ALL DONE' . PHP_EOL;
        exit();
        
    }
        
    protected function nodes()
    {
        return $this->getServiceLocator()->get('KofusNodeService');
    }
    
    protected function mailer()
    {
        return $this->getServiceLocator()->get('KofusMailerService');
    }
        
    
    
    
    
    
    
  
    
    

}