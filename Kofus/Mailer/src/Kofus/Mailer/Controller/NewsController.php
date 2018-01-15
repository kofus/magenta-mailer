<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class NewsController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
        
        $qb = $this->nodes()->createQueryBuilder('NS');
        $qb->orderBy('n.id', 'DESC');
        $paginator = $this->paginator($qb);
    	return new ViewModel(array(
    		'paginator' => $paginator
    	));
    }
    
    public function viewAction()
    {
        $this->archive()->uriStack()->push();
        
        $node = $this->nodes()->getNode($this->params('id'), 'NS');
        
        return new ViewModel(array(
            'entity' => $node
        ));
    }
    
    public function previewAction()
    {
        $node = $this->nodes()->getNode($this->params('id'), 'NS');
        
        $html = $this->mailer()->renderHtmlBody($node->getContentHtml());
        
        $response = $this->getResponse();
        $response->setContent($html);
        return $response;
    }
    
    public function addtaskAction()
    {
        $node = $this->nodes()->getNode($this->params('id'), 'NS');
        
        
        
        return new ViewModel();
        
    }
}
