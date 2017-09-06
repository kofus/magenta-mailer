<?php

namespace Kofus\Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class MailController extends AbstractActionController
{
    
    public function sentAction()
    {
        $this->archive()->uriStack()->push();
        
        $qb = $this->nodes()->createQueryBuilder('ML')
            ->where('n.timestampSent IS NOT NULL')
            ->orderBy('n.timestampSent', 'DESC');
        $paginator = $this->paginator($qb);
        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }
    
    public function previewAction()
    {
        $node = $this->nodes()->getNode($this->params('id'), 'ML');
        
        $body = $node->getBody();
        $headers = $node->getHeaders();
        $contentType = $headers->get('Content-Type');
        
        foreach ($body->getParts() as $part) {
            if (strpos($part->getType(), 'text/html') === false)
                continue;
                $s = $part->getContent();
                if ('base64' == $part->getEncoding())
                    $s = base64_decode($part->getContent());
        }
        return $this->getResponse()->setContent($s);
        
        
        
    }
    
    

}
