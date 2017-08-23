<?php

namespace Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;


class MailingController extends AbstractActionController
{
    public function dispatch(Request $request, Response $response=null)
    {
    	parent::dispatch($request, $response);
    	$this->layout('layout/admin');
    }
    
    
    
    public function listAction()
    {
        $this->uriStack()->push();
    	$entities = $this->em()->getRepository('Mailer\Entity\MailingEntity')->findAll();
    	return array(
    		'entities' => $entities
    	);
    }
    
    public function viewAction()
    {
        $this->uriStack()->push();
        $entity = $this->dm()->getEntity($this->params('id'), 'MAILING');
        return new ViewModel(array(
        	'entity' => $entity
        ));
    }
    
    public function runAction()
    {
        $entity = $this->dm()->getEntity($this->params('id'), 'MAILING');
        
        // HTML
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\RendererInterface');
        $model = new ViewModel(array(
        		'entity' => $entity->getTemplate(),
        		'content' => $entity->getTemplate()->getContentHtml()
        ));
        $model->setTemplate('mailtemplate/basic');
        $htmlMarkup = $renderer->render($model);
        
        $html = new MimePart($htmlMarkup);
        $html->type = "text/html";  
        $html->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
        
        /*
        $imageStream = file_get_contents('public/layout/exzentriker/img/logo.jpg');
        $logo = new MimePart($imageStream);
        $logo->type = 'image/jpg';
        //$logo->filename = 'bla.jpg';
        $logo->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
        $logo->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
        $logo->id = 'asdf';
        */

        $body = new MimeMessage();
        $body->setParts(array($html));        
        
        $message = new Message();
        $message->addFrom('mailer@die-exzentriker.de', 'Exzentriker Mail-System')
            //->addReplyTo("info@die-exzentriker.de", "Die Exzentriker")
            ->setSubject($entity->getTemplate()->getSubject())
            ->setBody($body);
        $message->getHeaders()->addHeaderLine('X-KOFUS-Mailing', $entity->getDataId());
        $message->setEncoding('UTF-8');
        

        $transport = new \Zend\Mail\Transport\Smtp();
        $transport->setOptions(new \Zend\Mail\Transport\SmtpOptions(array(
            'host' => 'smtp.die-exzentriker.de',
            'connection_class' => 'login',
            'connection_config' => array(
        	   'username' => 'mailer@die-exzentriker.de',
                'password' => 'hKRCsjQx4t%r'
            )
        )));
        
        // Recipients
        $subscriptions = $this->em()->getRepository('Mailer\Entity\SubscriptionEntity')->findBy(array('newsgroup' => $entity->getNewsgroup()));
        foreach ($subscriptions as $subscription) {
            print $subscription->getEmailAddress() . '<br>';
            $message->addTo($subscription->getEmailAddress()->getZendAddress());
            $transport->send($message);
        }
        
        die();
    }
}
