<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node;
use Zend\Mail\Headers;



/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_mails")
 */
class MailEntity implements Node\NodeInterface
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
     * @ORM\ManyToMany(targetEntity="Kofus\Mailer\Entity\ChannelEntity", inversedBy="mails")
     * @ORM\JoinTable(name="kofus_mailer_mails_channels",
     *     joinColumns={@ORM\JoinColumn(name="mail_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="channel_id", referencedColumnName="id")}
     * )
     */
    protected $channels = array();
    
    
    public function setChannels(array $channels)
    {
        $this->channels = $channels; return $this;
    }
    
    public function getChannels()
    {
        return $this->channels;
    }
    
    /**
     * @ORM\Column(nullable=true)
     */
    protected $systemId;
    
    public function setSystemId($value)
    {
        $this->systemId = $value; return $this;
    }
    
    public function getSystemId()
    {
        return $this->systemId;
    }
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $timestampCreated;
	
	public function setTimestampCreated(\DateTime $dt)
	{
	    $this->timestampCreated = $dt; return $this;
	}
	
	public function getTimestampCreated()
	{
	    return $this->timestampCreated;
	}
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timestampScheduled;
	
	public function setTimestampScheduled(\DateTime $dt=null)
	{
	    $this->timestampScheduled = $dt; return $this;
	}
	
	public function getTimestampScheduled()
	{
	    return $this->timestampScheduled;
	}
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timestampSent;
	
	public function setTimestampSent(\DateTime $dt=null)
	{
	    $this->timestampSent = $dt; return $this;
	}
	
	public function getTimestampSent()
	{
	    return $this->timestampSent;
	}
	
	
	public function getNodeType()
	{
		return 'ML';
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

