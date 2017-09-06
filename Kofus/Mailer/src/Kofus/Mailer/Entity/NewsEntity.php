<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node;



/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_news")
 */
class NewsEntity implements Node\NodeInterface, Node\EnableableNodeInterface
{
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
	 * @ORM\Column(type="boolean")
	 */
	protected $enabled = true;
	
	public function isEnabled($boolean=null)
	{
	    if ($boolean !== null)
	        $this->enabled = (bool) $boolean;
	        return $this->enabled;
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
	 * @ORM\Column(type="text")
	 */
	protected $contentText;
	
	public function setContentText($value)
	{
	    $this->contentText = $value; return $this;
	}
	
	public function getContentText()
	{
	    return $this->contentText;
	}
	
	
	/**
	 * @ORM\Column()
	 */
	protected $template;
	
	public function setTemplate($value)
	{
	    $this->template = $value; return $this;
	}
	
	public function getTemplate()
	{
	    return $this->template;
	}
	
	
	/**
	 * @ORM\Column(type="json_array")
	 */
	protected $channelIds = array();
	
	public function setChannelIds(array $values)
	{
	    $this->channelIds = $values; return $this;
	}
	
	public function getChannelIds()
	{
	    return $this->channelIds;
	}
	
	public function getNodeType()
	{
		return 'NS';
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

