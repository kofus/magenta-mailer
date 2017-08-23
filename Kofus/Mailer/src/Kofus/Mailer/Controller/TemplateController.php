<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class TemplateController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
    	$entities = $this->em()->getRepository('Kofus\Mailer\Entity\TemplateEntity')->findBy(array(), array('subject' => 'ASC'));
    	return new ViewModel(array(
    		'entities' => $entities
    	));
    }
    
    public function previewAction()
    {
        $entity = $this->dm()->getEntity($this->params('id'), 'MTMPL');
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\RendererInterface');
        
        $model = new ViewModel(array(
        	'entity' => $entity,
            'content' => $entity->getContentHtml()
        ));
        $model->setTemplate('mailtemplate/basic');
        echo $renderer->render($model);
        exit();
    }
    
    public function viewAction()
    {
        $this->uriStack()->push();
        $entity = $this->dm()->getEntity($this->params('id'), 'MTMPL');
        
        
        return new ViewModel(array(
        	'entity' => $entity,
        ));
    }
}
