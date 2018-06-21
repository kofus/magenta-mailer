<?php
namespace Kofus\Mailer\Form\Fieldset\Mail;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DispatchFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{

    public function init()
    {
        $this->setLabel('Versandeinstellungen');
        
        $el = new Element\DateTimeSelect('timestamp_scheduled', array(
            'label' => 'Versand geplant'
        ));
        $this->add($el);
        
        $el = new Element\Checkbox('enabled', array('label' => 'Freigegeben?'));
        $this->add($el);
        
        
    }

    public function getInputFilterSpecification()
    {
        $spec = array(
           
        );
        return $spec;
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
    
    protected function nodes()
    {
        return $this->getServiceLocator()->get('KofusNodeService');
    }
}

