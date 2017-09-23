<?php

namespace Kofus\Mailer;

interface NewsSubscriberInterface
{
    public function getEmailAddress();
    
    public function getMailerParams();
}