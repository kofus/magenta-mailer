<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use InformationRetrieval\Db\Sqlite\SeleniumDb;
use Kofus\Media\Entity\PdfEntity;




class ConsoleController extends AbstractActionController
{
    
    protected function processMailerParams(array $params)
    {
        if (isset($params['gender'])) {
            if ($params['gender'] == 'm') {
                $params['Lieber Herr'] = 'Lieber Herr';
                
            } elseif ($params['gender'] == 'f') {
                $params['Lieber Herr'] = 'Liebe Frau';
            }
        }
        return $params;
    }
    
    
    public function sendAction()
    {
        $qb = $this->nodes()->createQueryBuilder('ML');
        $qb->where('n.enabled = true')
            ->andWhere('n.status = 0')
            ->andWhere('n.timestampScheduled <= :now')
            ->setParameter('now', new \DateTime());
        $jobs = $qb->getQuery()->getResult();
        
        foreach ($jobs as $job) {
            $news = $job->getNews();
            echo 'Job ' . $job . PHP_EOL;
            echo 'News ' . $news . PHP_EOL;
            
            foreach ($job->getChannels() as $channel) {
                echo 'Channel ' . $channel . PHP_EOL;
                echo PHP_EOL . 'Start...' . PHP_EOL;
                
                $subscriptions = $this->nodes()->getRepository('SCP')->findBy(array('channel' => $channel));
                foreach ($subscriptions as $subscription) {
                    $subscriberId = $subscription->getSubscriberId();
                    $subscriber = $this->nodes()->getNode($subscriberId);
                    
                    echo $subscriber . ' ' . $subscriber->getEmailAddress() . PHP_EOL;
                    $mailerParams = $this->processMailerParams($subscriber->getMailerParams());
                    $viewParams = array(
                        'content' => $news->getContentHtml(),
                        'subscriber' => $subscriber
                    );
                    $msg = $this->mailer()->createHtmlMessage($viewParams, $mailerParams);
                    foreach ($job->getParams() as $method => $value) {
                        $method = 'set' . $method;
                        $msg->$method($value);
                    }
                    $msg->addTo($subscriber->getEmailAddress());
                    $msg->setSubject($news->getSubject());
                    
                    sleep(5);
                    $this->mailer()->enqueue($msg);
                }
            }
            echo PHP_EOL;
            $job->setStatus(2);
            $job->setTimestampCompleted(new \DateTime());
            $this->em()->persist($job);
            $this->em()->flush();
        }
        echo PHP_EOL;
    }
}
