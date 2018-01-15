<?php
namespace Kofus\Mailer\Form\Fieldset\Newsletter;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;


class AdministerFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $channels = array();
    
    public function setChannels(array $channels)
    {
        $this->channels = $channels; return $this;
    }
    
    public function getChannels()
    {
        return $this->channels; 
    }
    
	public function init()
	{
	    $valueOptions = array();
	    foreach ($this->getChannels() as $channel)
	        $valueOptions[$channel->getNodeId()] = $channel->getTitle();
	    
		$el = new Element\MultiCheckbox('channels');
		$el->setValueOptions($valueOptions);
		$this->add($el);
		
		$el = new Element\Submit('submit');
		$el->setLabel('Speichern');
		$this->add($el);
		
	}

	public function getInputFilterSpecification()
	{
	    $spec = array(
	        'channels' => array(
	        	'required' => false
	        ),
	    );
	    return $spec;
	}
	
	
}

