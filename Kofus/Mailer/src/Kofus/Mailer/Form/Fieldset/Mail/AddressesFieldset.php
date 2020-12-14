<?php
namespace Kofus\Mailer\Form\Fieldset\Mail;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddressesFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{

    public function init()
    {
        $config = $this->getServiceLocator()->get('KofusConfig');
        
        $channels = $this->nodes()->getRepository('NCH')->findBy(array(), array('title' => 'ASC'));
        $valueOptions = array();
        foreach ($channels as $channel)
            $valueOptions[$channel->getNodeId()] = $channel->getTitle();
        
        $el = new Element\MultiCheckbox('channels', array('label' => 'To'));
        $el->setValueOptions($valueOptions);
        $this->add($el);
        
        $addresses = array();
        foreach ($config->get('mailer.addresses') as $address)
            $addresses[$address->toString()] = $address->toString();
        
        $el = new Element\Select('from', array('label' => 'From'));
        $el->setValueOptions($addresses);
        $this->add($el);
        
        $el = new Element\Select('bcc', array('label' => 'BCC'));
        $el->setEmptyOption('');
        $el->setValueOptions($addresses);
        $this->add($el);
        
        
    }

    public function getInputFilterSpecification()
    {
        $spec = array(
            'channels' => array(
                'required' => false
            ),
            'bcc' => array(
                'required' => false
            ),
            'from' => array(
                'required' => true
            )
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

