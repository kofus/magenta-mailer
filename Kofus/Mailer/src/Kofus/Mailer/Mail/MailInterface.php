<?php
namespace Kofus\Mailer\Mail;

use Kofus\Mailer\Entity\TemplateEntity;

interface MailInterface
{
    public function setValues(array $values);
    
    public function getValues();
    
    public function setParams(array $params);
    
    public function getParams();
    
    public function setTemplate(TemplateEntity $template);
    
    public function getTemplate();
    
    public function createMessage();
}