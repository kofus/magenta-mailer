<?php
namespace Kofus\Mailer\Form\Fieldset\News;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{

    public function init()
    {
        $config = $this->getServiceLocator()->get('KofusConfig');
        
        $el = new Element\Text('subject', array(
            'label' => 'Betreff'
        ));
        $this->add($el);
        
        $el = new Element\Textarea('content_html', array(
            'label' => 'Inhalt (HTML)'
        ));
        //$el->setAttribute('class', 'ckeditor');
        $this->add($el);
        
        $el = new Element\Textarea('content_text', array(
            'label' => 'Inhalt (Text)'
        ));
        $this->add($el);
        
        $valueOptions = array();
        foreach ($config->get('mailer.templates.enabled', array()) as $template)
            $valueOptions[$template] = $template;
        
        $el = new Element\Select('template', array(
            'label' => 'Template'
        ));
        $el->setValueOptions($valueOptions);
        $this->add($el);
        
        $el = new Element\Text('system_id', array(
            'label' => 'System-ID'
        ));
        $this->add($el);
    }

    public function getInputFilterSpecification()
    {
        $spec = array(
            'subject' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'Zend\Filter\StringTrim'
                    )
                )
            ),
            'content_html' => array(
                'required' => true
            ),
            'content_text' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'Zend\Filter\StringTrim'
                    )
                )
            
            ),
            'template' => array(
                'required' => true
            ),
            'system_id' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'Zend\Filter\StringTrim'
                    ),
                    array(
                        'name' => 'Zend\Filter\ToNull'
                    ),
                )
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
}

