<?php

namespace Kofus\Mailer\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class MailerHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $sm;
    protected $config;
    
    public function __invoke()
    {
    	return $this->getServiceLocator()->get('KofusMailerService');
    }
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->sm = $serviceLocator;
    }
    
    public function getServiceLocator()
    {
    	return $this->sm->getServiceLocator();
    }
}


