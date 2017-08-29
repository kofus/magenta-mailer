<?php
namespace Kofus\Mailer\Service;

use Zend\Mail;
use Kofus\System\Service\AbstractService;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Kofus\Mailer\Entity\NewsgroupEntity;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Kofus\Mailer\Entity\NewsSubscriberEntity;
use Kofus\Mailer\Entity\NewsSubscriptionEntity;
use Kofus\Mailer\Entity\NewsEntity;


class MailerService extends AbstractService implements EventManagerAwareInterface
{
    public function countSubscriptions(NewsgroupEntity $newsgroup)
    {
        return 1;
        $qb = $this->em()->createQueryBuilder();
        $qb->select($qb->expr()->count('s.id'))
            ->from('Kofus\Mailer\Entity\SubscriptionEntity', 's')
            ->where('s.newsgroups HAS :newsgroup')
            ->setParameter('newsgroup', $newsgroup);
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    protected $transport;
    
    protected function sendmail($msg)
    {
        if (! $this->transport)
            $this->transport = new Mail\Transport\Sendmail();
        
        $this->getEventManager()->trigger('beforeSend', $this, array($msg));
        $this->transport->send($msg);
        $this->getEventManager()->trigger('send', $this, array($msg));
    }
    
    public function renderMailHtmlBody(NewsEntity $entity, array $variables=array())
    {
        $templateName = $entity->getTemplate();
        
        // Renderer
        $renderer = new \Zend\View\Renderer\PhpRenderer();
        
        // Add view helpers
        $pluginManager = $renderer->getHelperPluginManager();
        foreach ($this->config()->get('view_helpers.invokables') as $name => $invokableClass)
            $pluginManager->setInvokableClass($name, $invokableClass);
            $pluginManager->get('url')->setRouter($this->getServiceLocator()->get('Router'));
            
            // Resolver
            $resolver = new \Zend\View\Resolver\TemplateMapResolver();
            $map = array();
            foreach ($this->config()->get('mailer.templates.available') as $name => $data)
                $map[$name] = $data['base_uri'] . '/' . $data['filename'];
                $resolver->setMap($map);
                $renderer->setResolver($resolver);
                
                // Layout as view
                $viewModel = new \Zend\View\Model\ViewModel();
                $viewModel->setTemplate($templateName);
                $viewModel->setVariables(array('content' => $entity->getContentHtml()));
                
                
                // Add styles
                $templateConfig = $this->config()->get('mailer.templates.available.' . $templateName, array());
                if (isset($templateConfig['css'])) {
                    $css = '';
                    foreach ($templateConfig['css'] as $filename)
                        $css .= file_get_contents($templateConfig['base_uri'] . '/' . $filename) . ' ';
                        $renderer->getHelperPluginManager()->get('headStyle')->appendStyle($css);
                }
                
                // Render html
                $html = $renderer->render($viewModel);
                
                return $html;
                
    }
    
    public function subscribe(NewsSubscriberEntity $subscriber, array $channels=array())
    {
        $this->em()->persist($subscriber);
        $token = \Zend\Math\Rand::getString(32, 'abcdefghijklmnopqrstuvwxyz0123456789');
        foreach ($channels as $channel) {
            $subscription = $this->nodes()->getRepository('SCP')->findOneBy(array('channel' => $channel, 'subscriber' => $subscriber));
            if (! $subscription) {
                $subscription = new NewsSubscriptionEntity();
                $subscription->setSubscriber($subscriber);
                $subscription->setChannel($channel);
            }
            $subscription->setActivationToken($token);
            $this->em()->persist($subscription);
        }
        $this->em()->flush();
    }
    
    
    public function send(Mail\Message $msg, $newsgroup=null)
    {
        if ($newsgroup) {
            if (is_string($newsgroup))
                $newsgroup = $this->nodes()->getNode($newsgroup, 'NEWSGROUP');
            $subscriptions = $this->nodes()->getRepository('SUBSCR')->findBy(array('newsgroup' => $newsgroup));
            foreach ($subscriptions as $subscription) {
                $msg->setTo($subscription->getEmailAddress(), $subscription->getName());
                $this->sendmail($msg);
            }
        } else {
            $this->sendmail($msg);
        }
        return $this;
    }
    
    public function createMail($key='default', $template=null, array $values=array(), array $params=array())
    {
        $config = $this->config()->get('mailer.message.' . $key);
        if (! $config)
            throw new \Exception('Mail message "'.$key.'" not found in config');
        
        if (! isset($config['class']))
            throw new \Exception('No class specification found for mail message "'.$key.'"');

        // Create class
        $classname = $config['class'];
        $mail = new $classname();
        if (! $mail instanceof \Kofus\Mailer\Mail\MailInterface)
            throw new \Exception($classname . ' must implement MailInterface');
        
        // Merge values
        if ($this->config()->get('mailer.values')) 
            $values = array_merge($this->config()->get('mailer.values'), $values);
        if (isset($config['values']))
            $values = array_merge($config['values'], $values);
        $mail->setValues($values);
        
        // Merge params
        if ($this->config()->get('mailer.params')) 
            $params = array_merge($this->config()->get('mailer.params'), $params);
        if (isset($config['params']))
            $params = array_merge($config['params'], $params);
        $mail->setParams($params);
        
        // Template
        if (! $template && isset($config['template']))
            $template = $config['template'];
        if ($template) {
            $templateNode = $this->nodes()->getNode($template, 'MTMPL');
            if (! $templateNode)
                throw new \Exception('Mail template "'.$template.'" not found'); 
            $mail->setTemplate($templateNode);
        }
            
        // ServiceLocator?
        if ($mail instanceof ServiceLocatorAwareInterface)
            $mail->setServiceLocator($this->getServiceLocator());
        
        return $mail;
    }
    
    protected $events;
    
    public function setEventManager(EventManagerInterface $events)
    {
    	$events->setIdentifiers(array('KOFUS_MAILER', get_called_class()));
    	$this->events = $events;
    	return $this;
    }
    
    public function getEventManager()
    {
    	if (null === $this->events)
    		$this->setEventManager(new EventManager());
    	 
    	return $this->events;
    }
    
}