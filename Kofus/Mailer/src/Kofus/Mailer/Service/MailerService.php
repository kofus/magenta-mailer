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
    
    /**
     * Render markup into template, supporting view helpers
     * @param string $markup
     * @param string $template
     * @return string
     */
        public function renderHtmlBody(array $viewParams=array(), $template='default')
    {
        // Renderer
        $renderer = $this->getPhpRenderer(); 
        
        // Resolver
        $resolver = new \Zend\View\Resolver\TemplateMapResolver();
        $map = array();
        foreach ($this->config()->get('mailer.templates.available', array()) as $name => $data)
            $map[$name] = $data['base_uri'] . '/' . $data['filename'];
        if (! isset($map[$template]))
            return $viewParams['content'];
        $resolver->setMap($map);
        $renderer->setResolver($resolver);
        
        // Layout as view
        $viewModel = new \Zend\View\Model\ViewModel($viewParams);
        $viewModel->setTemplate($template);
        
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
    
    protected function getPhpRenderer()
    {
        $renderer = new \Zend\View\Renderer\PhpRenderer();
        
        $pluginManager = $renderer->getHelperPluginManager();
        foreach ($this->config()->get('view_helpers.invokables') as $name => $invokableClass)
            $pluginManager->setInvokableClass($name, $invokableClass);
        $pluginManager->get('url')->setRouter($this->getServiceLocator()->get('Router'));
        
        return $renderer;
    }
    
    /**
     * Register subscriber for given channels
     * @param NewsSubscriberEntity $subscriber
     * @param array $channels
     */
    public function subscribe($subscriberId, array $channels=array(), $triggerOptIn=false)
    {
        $subscriber = $this->nodes()->getNode($subscriberId);
        if (! $subscriber || ! $subscriber instanceof \Kofus\Mailer\NewsSubscriberInterface)
            throw new \Exception($subscriberId . ' is not a valid subscriber');
        $token = \Zend\Math\Rand::getString(16, 'abcdefghijklmnopqrstuvwxyz0123456789');
        foreach ($channels as $channel) {
            $existingSubscription = $this->nodes()->getRepository('SCP')->findOneBy(array('channel' => $channel, 'subscriberId' => $subscriberId));
            
            if (! $existingSubscription) {
                $subscription = new NewsSubscriptionEntity();
                $subscription->setSubscriberId($subscriberId);
                $subscription->setChannel($channel);
            } else {
                $subscription = $existingSubscription;
            }
            
            if (! $existingSubscription) {
                if (! $triggerOptIn)
                    $subscription->setTimestampActivationSubscriber(new \DateTime());
                $subscription->setActivationToken($token);
                $this->em()->persist($subscription);
                $this->em()->flush();
            }
            
            // Send mail with activation link
            if ($triggerOptIn) {
                $urlHelper = $this->getPhpRenderer()->getHelperPluginManager()->get('url');
                $link = $urlHelper('kofus_mailer', array('controller' => 'newsletter', 'action' => 'opt-in', 'id' => $token), array('force_canonical' => true));
                $tokens = $subscriber->getMailerParams();
                $tokens['host'] = $_SERVER['HTTP_HOST'];
                $tokens['link'] = $link;
                $news = $this->nodes()->getRepository('NS')->findOneBy(array('systemId' => 'OPT_IN'));
                if ($news) {
                    $msg = $this->createHtmlMessage(array('content' => $news->getContentHtml()), $tokens);
                    $msg->setSubject($news->getSubject());
                } else {
                    $msg = $this->createHtmlMessage(array('content' => '<p>Guten Tag,</p><p>vielen Dank für Ihre Newsletter-Registrierung auf <a href="{host}">{host}</a>.</p><p>Bitte klicken Sie auf folgenden Link, um Ihre Anmeldung abzuschließen:</p><p><a href="{link}">{link}</p>'), $tokens);
                    $msg->setSubject('Ihre Newsletter-Anmeldung');
                }
                $msg->setTo($subscriber->getEmailAddress());
                $this->send($msg);
            }
        }
    }
    
    public function unsubscribe($subscriberId, array $channels=array())
    {
        $subscriber = $this->nodes()->getNode($subscriberId);
        if (! $subscriber || ! $subscriber instanceof \Kofus\Mailer\NewsSubscriberInterface)
            throw new \Exception($subscriberId . ' is not a valid subscriber');
        foreach ($channels as $channel) {
            $subscription = $this->nodes()->getRepository('SCP')->findOneBy(array('channel' => $channel, 'subscriberId' => $subscriberId));
            if ($subscription)
                $this->em()->remove($subscription);
        }
        $this->em()->flush();
        
    }
    
    /**
     * Activate all subscriptions marked by the given activation token
     * @param string $token
     * @return array
     */
    public function optIn($token)
    {
        $subscriptions = array();
        $timestamp = new \DateTime();
        foreach ($this->nodes()->getRepository('SCP')->findBy(array('activationToken' => $token))as $subscription) {
            if (! $subscription->getTimestampActivationSubscriber()) {
                $subscription->setTimestampActivationSubscriber($timestamp);
                $subscriptions[] = $subscription;
                $this->em()->persist($subscription);
            }
        }
        $this->em()->flush();
        return $subscriptions;
    }
    
    public function form2Html(\Zend\Form\Form $form)
    {
        $html = '';
        foreach ($form as $fieldset) {
            if ($fieldset instanceof \Zend\Form\FieldsetInterface) {
                if ($fieldset->getLabel())
                    $html .= '<h2>' . htmlentities($fieldset->getLabel()) . '</h2>';
                $html .= '<table>';
                foreach ($fieldset as $element) {
                    switch (get_class($element)) {
                        case 'Zend\Form\Element\Text':
                        case 'Zend\Form\Element\Textarea':
                            $html .= '<tr>';
                            $html .= '<th align="left">' . htmlentities($element->getLabel()) . ':&nbsp;</th>';
                            $html .= '<td align="left">' . htmlentities($element->getValue()) . '</td>';
                            $html .= '</tr>';
                            break;
                        
                        //default:
                          //  print get_class($element);
                    }
                    
                }
                $html .= '</table>';
            }
        }
        return $html;
    }
    
    public function createHtmlMessage(array $viewParams=array(), array $tokens=array())
    {
        // Preprocess tokens
        if (isset($tokens['gender']) && ! isset($tokens['anrede'])) {
            if ($tokens['gender'] == 'f') {
                $tokens['anrede'] = 'Frau';
            } else {
                $tokens['anrede'] = 'Herr';
            }
        }
        
        // Populate tokens
        foreach ($tokens as $key => $value)
            $viewParams['content'] = str_replace('{' . $key . '}', $value, $viewParams['content']);
            
        // Render template
        $markup = $this->renderHtmlBody($viewParams);
            
        $html = new \Zend\Mime\Part($markup);
        $html->type = 'text/html';
        $html->encoding = 'base64';
        $html->charset = 'UTF-8';
        
        $body = new \Zend\Mime\Message();
        $body->setParts(array($html));
        
        $msg = new \Zend\Mail\Message();
        $msg->setBody($body);
        
        foreach ($this->config()->get('mailer.params') as $key => $value) {
            $method = 'set' . $key;
            $msg->$method($value);
        }
        
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