<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node;



/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_channels")
 */
class NewsChannelEntity implements Node\NodeInterface, Node\EnableableNodeInterface
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
	 * @ORM\Column(type="boolean")
	 */
	protected $enabled = true;
	
	public function isEnabled($boolean=null)
	{
	    if ($boolean !== null)
	        $this->enabled = (bool) $boolean;
	        return $this->enabled;
	}
	
	public function getNodeType()
	{
		return 'NCH';
	}
	
	public function getNodeId()
	{
	    return $this->getNodeType() . $this->getId();
	}
	
	public function __toString()
	{
		return $this->getTitle() . ' (' . $this->getNodeId() . ')';	
	}
}

