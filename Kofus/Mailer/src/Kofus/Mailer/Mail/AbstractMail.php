<?php
namespace Kofus\Mailer\Mail;

use Kofus\Mailer\Entity\TemplateEntity;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;



abstract class AbstractMail implements ServiceLocatorAwareInterface
{
    protected $values = array();
    
    public function setValues(array $values)
    {
        $this->values = $values; return $this;
    }
    
    public function getValues()
    {
        return $this->values;
    }
    
    public function setValue($key, $value)
    {
        $this->values[$key] = $value; return $this;
    }
    
    public function getValue($key, $default=null)
    {
        if (isset($this->values))
            return $this->values[$key];
        return $default;
    }
    
    protected $params = array();
    
    public function setParams(array $params)
    {
        $this->params = $params; return $this;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    protected $template;
    
    public function setTemplate(TemplateEntity $template)
    {
        $this->template = $template; return $this;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function createMessage()
    {
        // Init
        $msg = new Message();
        $msg->setEncoding('UTF-8');
        
        // Params
        foreach ($this->getParams() as $key => $value) {
            $method = 'set' . ucfirst($key);
            $msg->$method($value);
        }
        
        // Template
        if ($this->getTemplate()) {
            
            // I18n
            $translations = $this->getServiceLocator()->get('KofusTranslationService');
            $translator = $this->getServiceLocator()->get('translator');
            
            $template = $this->getTemplate();
            $markup = $translations->translateNode($template, 'getContentHtml');
            
            // Values
            $subject = $translations->translateNode($template, 'getSubject');
            foreach ($this->getValues() as $key => $value) {
                $markup = str_replace('{' . $key . '}', $value, $markup);
                $subject = str_replace('{' . $key . '}', $value, $subject);
            }
            $msg->setSubject($subject);
            
            
            // Render template
            $layoutPath = $this->getServiceLocator()->get('KofusConfig')->get('mailer.layout.' . $template->getLayout());
            if (! $layoutPath)
                throw new \Exception('Mail layout "'.$template->getLayout().'" not defined');
            $renderer = $this->getServiceLocator()->get('ViewRenderer');
            $model = new ViewModel(array('content' => $markup));
            $model->setTemplate($layoutPath);
            
            // HTML
            $html = new MimePart($renderer->render($model));
            $html->type = 'text/html';
            $html->encoding = 'base64';
            $html->charset = 'UTF-8';
            $body = new MimeMessage();
            $body->setParts(array($html));
            
            $msg->setBody($body);
            
        }
        
        return $msg;
    }
    
    protected $sm;
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->sm = $serviceLocator;
    }
    
    public function getServiceLocator()
    {
    	return $this->sm;
    }
    
}