<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;


class IndexController extends AbstractActionController
{
    public function systemmailAction()
    {
        $form = new \Zend\Form\Form();
        
        $form = $this->formBuilder()
            ->addFieldset(new \Kofus\Mailer\Form\Fieldset\SystemMail\MasterFieldset('master'))
            ->setLabelSize('col-sm-3')->setFieldSize('sm-9')
            ->buildForm()
            ->add(new \Zend\Form\Element\Submit('submit', array('label' => 'Submit')));
            
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                
                // Layout
                $layoutPath = $this->config()->get('mailer.layout.' . $form->get('master')->get('layout')->getValue());
                $renderer = $this->getServiceLocator()->get('ViewRenderer');
                $model = new ViewModel(array('content' => $form->get('master')->get('content_html')->getValue()));
                $model->setTemplate($layoutPath);                
                
                // HTML
                $html = new MimePart($renderer->render($model));
                $html->type = 'text/html';
                $html->encoding = 'base64';
                $html->charset = 'UTF-8';
                $body = new MimeMessage();
                $body->setParts(array($html));
                
                $msg = new \Zend\Mail\Message();
                $msg->setBody($body);
                $msg->setSubject($form->get('master')->get('subject')->getValue());
                $msg->setTo($form->get('master')->get('to')->getValue());
                $msg->setFrom($form->get('master')->get('from')->getValue());
                
                $this->mailer()->send($msg);
                
                $this->flashMessenger()->addSuccessMessage('Message sent');
                return $this->redirect()->toUrl($this->archive()->uriStack()->pop());
            }
        } else {
            if ($this->config()->get('mailer.params.from'))
                $form->get('master')->get('from')->setValue($this->config()->get('mailer.params.from')->toString());
            if ($this->config()->get('mailer.params.to'))
            	$form->get('master')->get('to')->setValue($this->config()->get('mailer.params.to')->toString());
            
        }
        
        return new ViewModel(array(
        	'form' => $form->prepare()
        ));
        
    }
    
}
