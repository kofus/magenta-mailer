<?php
namespace Kofus\Mailer\Form\Fieldset\Subscription;

use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{

    public function init()
    {
        $el = new Element\Select('newsgroup', array(
            'label' => 'Newsgroup'
        ));
        $el->setValueOptions($this->getNewsgroups());
        $this->add($el);
        
        $el = new Element\Text('email', array(
            'label' => 'Email'
        ));
        $this->add($el);
        
        $el = new Element\Select('gender', array(
            'label' => 'Ms./Mr.'
        ));
        $el->setValueOptions(array(
            'f' => 'Ms.',
            'm' => 'Mr.'
        ));
        $el->setEmptyOption('');
        $this->add($el);
        
        $el = new Element\Text('title', array(
            'label' => 'Title'
        ));
        $this->add($el);
        
        $el = new Element\Text('first_name', array(
            'label' => 'First name'
        ));
        $this->add($el);
        
        $el = new Element\Text('last_name', array(
            'label' => 'Last name'
        ));
        $this->add($el);
        
        $el = new Element\Text('name', array(
            'label' => 'Display Name'
        ));
        $el->setAttribute('placeholder', 'wird automatisch erzeugt');
        $this->add($el);
    }

    public function getInputFilterSpecification()
    {
        return array(
            'newsgroup' => array(
                'required' => true
            ),
            'email' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'Zend\Filter\StringTrim'
                    ),
                    array(
                        'name' => 'Zend\Filter\ToNull'
                    ),
                    array(
                        'name' => 'Zend\Filter\StringToLower'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\EmailAddress'
                    )
                )
            ),
            'gender' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'Zend\Filter\ToNull'
                    )
                )
            ),
            'title' => array(
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'Zend\Filter\StringTrim'
                    ),
                    array(
                        'name' => 'Zend\Filter\ToNull'
                    )
                )
            ),
            'first_name' => array(
                'required' => false,
                'filters' => array(
            				array(
            						'name' => 'Zend\Filter\StringTrim'
            				),
            				array(
            						'name' => 'Zend\Filter\ToNull'
            				)
            		)
            ),
            'last_name' => array(
                'required' => false,
                'filters' => array(
            				array(
            						'name' => 'Zend\Filter\StringTrim'
            				),
            				array(
            						'name' => 'Zend\Filter\ToNull'
            				)
            		)
            ),
            'name' => array(
                'required' => false,
                'filters' => array(
            				array(
            						'name' => 'Zend\Filter\StringTrim'
            				),
            				array(
            						'name' => 'Zend\Filter\ToNull'
            				)
            		)
            ),
            
        );
    }

    protected function getNewsgroups()
    {
        $nodes = $this->nodes()
            ->getRepository('NEWSGROUP')
            ->findAll();
        $valueOptions = array();
        foreach ($nodes as $node)
            $valueOptions[$node->getNodeId()] = $node->getTitle();
        return $valueOptions;
    }

    protected function nodes()
    {
        return $this->getServiceLocator()->get('KofusNodeService');
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



