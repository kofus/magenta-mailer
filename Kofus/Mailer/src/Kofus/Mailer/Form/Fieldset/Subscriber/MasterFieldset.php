<?php
namespace Kofus\Mailer\Form\Fieldset\Subscriber;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class MasterFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{

    public function init()
    {
        $el = new Element\Text('email', array(
            'label' => 'E-Mail'
        ));
        $this->add($el);
        
        $el = new Element\Text('name', array(
            'label' => 'Name'
        ));
        $this->add($el);
        
        $channels = $this->nodes()->getRepository('NCH')->findBy(array(), array('title' => 'ASC'));
        $valueOptions = array();
        foreach ($channels as $channel)
            $valueOptions[$channel->getNodeId()] = $channel->getTitle();
        
        $el = new Element\MultiCheckbox('channels', array('label' => 'Kanäle', 'node-type' => 'NCH'));
        $el->setValueOptions($valueOptions);
        $this->add($el);
        
        $el = new Element\Text('uri_segment', array('label' => 'URI-Segment'));
        $el->setAttribute('placeholder', 'wird automatisch generiert, wenn leer...');
        $this->add($el);
        
        $el = new Element\Checkbox('tester', array('label' => 'Tester?'));
        $this->add($el);
           
        
    }

    public function getInputFilterSpecification()
    {
        return array(
            'email' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'stringtrim'
                    ),
                    array('name' => 'stringtolower')
                ),
                'validators' => array(
                    array('name' => 'emailaddress')
                )
            ),
            'name' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'stringtrim'),
                    array('name' => 'tonull')
                )
            ),
            'channels' => array(
                'required' => false
            ),
            'uri_segment' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'alnum')
                )
            ),
            'tester' => array(
                'required' => false
            )
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

