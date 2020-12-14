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
        //$this->setLabel('Versandeinstellungen');
        
        $el = new Element\DateTimeSelect('timestamp_scheduled', array(
            'label' => 'Scheduled'
        ));
        $el->setShouldCreateEmptyOption(true);
        $this->add($el);
        
        $el = new Element\Checkbox('enabled', array('label' => 'enabled?'));
        $this->add($el);
        
        
    }

    public function getInputFilterSpecification()
    {
        return array(
            'timestamp_scheduled' => array('required' => false, array('filters' => array('name' => 'tonull')))
        );
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

