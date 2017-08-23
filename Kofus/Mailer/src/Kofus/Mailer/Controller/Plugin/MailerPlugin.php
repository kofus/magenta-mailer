<?php

namespace Kofus\Mailer\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;


class MailerPlugin extends AbstractPlugin
{
    protected $plugin;
    
    /**
	 * @return Data\Service\LuceneService 
	 */
    public function __invoke()
    {
        if (! $this->plugin)
            $this->plugin = $this->getController()->getServiceLocator()->get('KofusMailerService');
        
        return $this->plugin;
	}
    

}