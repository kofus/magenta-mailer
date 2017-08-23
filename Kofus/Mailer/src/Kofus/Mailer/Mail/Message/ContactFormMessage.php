<?php

namespace Mailer\Mail\Message;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;


class ContactFormMessage extends Message
{
    protected $params;
    protected $msg;
    
    public function __construct(\Zend\Form\Form $form)
    {
        $this->form = $form;
    }
    
    public function init()
    {
        $filter = new \Cms\Filter\FormToHtml();
        $markup = $filter->filter($this->form);
        
        $html = new MimePart($markup);
        $html->type = 'text/html';
        
        $body = new MimeMessage();
        $body->setParts(array($html));
        
        $this->setSubject('Anfrage über ' . $_SERVER['HTTP_HOST']);
        $this->setBody($body);
    }
}