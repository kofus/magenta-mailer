<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class MailController extends AbstractActionController
{
    public function listAction()
    {
        $this->archive()->uriStack()->push();
        $qb = $this->nodes()->createQueryBuilder('ML');
        $qb->orderBy('n.timestampCreated', 'DESC');
        
        return new ViewModel(array(
            'paginator' => $this->paginator($qb)
        ));
    }
    
    public function previewAction()
    {
        $node = $this->nodes()->getNode($this->params('id'), 'ML');
        
        $html = $this->mailer()->renderHtmlBody(array('content' => $node->getContentHtml()), $node->getTemplate());
        
        $response = $this->getResponse();
        $response->setContent($html);
        return $response;
    }
    
    public function resetAction()
    {
        $node = $this->nodes()->getNode($this->params('id'), 'ML');
        $node->setTimestampScheduled(null);
        $node->setTimestampSent(null);
        $node->isEnabled(false);
        $this->em()->persist($node);
        $this->em()->flush();
        return $this->redirect()->toUrl($this->archive()->uriStack()->pop());
    }
    
    

}
