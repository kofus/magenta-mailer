<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node\NodeInterface;
use Kofus\System\Node\TranslatableNodeInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_templates")
 *
 */
class TemplateEntity implements NodeInterface, TranslatableNodeInterface
{
    
    public function getTranslatableMethods()
    {
        return array(
        	'getSubject' => 'subject',
            'getContentHtml' => 'content_html'
        );
    }
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;
    
    public function getId()
    {
    	return $this->id;
    }
	
	/**
	 * @ORM\Column()
	 */
	protected $title;
	
	public function setTitle($value)
	{
	    $this->title = $value; return $this;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
    /**
	 * @ORM\Column()
	 */
	protected $subject;
	
	public function setSubject($value)
	{
	    $this->subject = $value; return $this;
	}
	
	public function getSubject()
	{
		return $this->subject;
	}
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $contentHtml;
	
	public function setContentHtml($value)
	{
		$this->contentHtml = $value; return $this;
	}
	
	public function getContentHtml()
	{
		return $this->contentHtml;
	}
	
	/**
	 * @ORM\Column()
	 */
	protected $layout;
	
	public function setLayout($value)
	{
		$this->layout = $value; return $this;
	}
	
	public function getLayout()
	{
		return $this->layout;
	}
	
	public function getNodeType()
	{
		return 'MTMPL';
	}
	
	public function getNodeId()
	{
	    return $this->getNodeType() . $this->getId();
	}
	
	public function __toString()
	{
		return $this->getSubject() . ' (' . $this->getNodeId() . ')';	
	}
}

