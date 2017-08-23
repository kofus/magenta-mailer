<?php
namespace Kofus\Mailer\Mail;

interface MessageInterface
{
    public function setValues(array $values);
    
    public function getValues();
    
    public function setParams(array $params);
    
    public function getParams();
    
    public function getMessage();
}