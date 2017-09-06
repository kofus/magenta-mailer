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
    /*
    protected $transport;
    
    protected function sendmail($msg)
    {
        if (! $this->transport)
            $this->transport = new Mail\Transport\Sendmail();
        
        $this->getEventManager()->trigger('beforeSend', $this, array($msg));
        $this->transport->send($msg);
        $this->getEventManager()->trigger('send', $this, array($msg));
    } */
    
    public function renderHtmlBody($markup, $template='default')
    {
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
                $viewModel->setTemplate($template);
                $viewModel->setVariables(array('content' => $markup));
                
                
                // Add styles
                $templateConfig = $this->config()->get('mailer.templates.available.' . $template, array());
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
            $this->em()->flush();
            
            // Send mail with activation link
            $tokens = array('host' => $_SERVER['HTTP_HOST'], 'link' => $token);
            $msg = $this->createHtmlMessage('<p>Guten Tag,</p><p>vielen Dank für Ihre Newsletter-Registrierung auf <a href="{host}">{host}</a>.</p><p>Bitte klicken Sie auf folgenden Link, um Ihre Anmeldung abzuschließen:</p><p><a href="{link}">{link}</p>', $tokens);
            $msg->setTo($subscriber->getEmailAddress());
            $msg->setSubject('Ihre Newsletter-Anmeldung');
            $this->send($msg);
        }
    }
    
    public function createHtmlMessage($markup, array $tokens=array())
    {
        // Populate tokens
        foreach ($tokens as $key => $value)
            $markup = str_replace('{' . $key . '}', $value, $markup);
            
        // Render template
        $markup = $this->renderHtmlBody($markup);
            
        $html = new \Zend\Mime\Part($markup);
        $html->type = 'text/html';
        $html->encoding = 'base64';
        $html->charset = 'UTF-8';
        
        $body = new \Zend\Mime\Message();
        $body->setParts(array($html));
        
        $msg = new \Zend\Mail\Message();
        $msg->setBody($body);
        
        if ($this->config()->get('mailer.params.from'))
            $msg->setFrom($this->config()->get('mailer.params.from'));
        
        return $msg;
    }
    
    
    
    public function send($mixed)
    {
        if ($mixed instanceof \Kofus\Mailer\Entity\MailEntity) {
            $entity = $mixed;
            $msg = new \Zend\Mail\Message();
            $msg->setEncoding($entity->getEncoding());
            $msg->setSubject($entity->getSubject());
            $msg->setHeaders($entity->getHeaders());
            $msg->setBody($entity->getBody());
            
        } elseif ($mixed instanceof \Zend\Mail\Message) {
            $msg = $mixed;
        } else {
            throw new \Exception('Cannot send object of type ' . get_class($mixed));
        }
        
        $transport = new \Zend\Mail\Transport\Sendmail();
        $transport->send($msg);
    }
    
    public function enqueue(\Zend\Mail\Message $msg, \DateTime $scheduled=null, $entity=null)
    {
        if (! $entity)
            $entity = new \Kofus\Mailer\Entity\MailEntity();
        $entity->setEncoding($msg->getEncoding());
        $entity->setSubject($msg->getSubject());
        $entity->setHeaders($msg->getHeaders());
        $entity->setBody($msg->getBody());
        $entity->setBodyText($msg->getBodyText());
        $entity->setTimestampCreated(new \DateTime());
        
        
        if ($scheduled) {
            $entity->setTimestampScheduled($scheduled);
        } else {
            $entity->setTimestampSent(new \DateTime());
            $this->send($entity);
        }
        
        $this->em()->persist($entity);
        $this->em()->flush();
        return $entity;
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