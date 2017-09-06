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
    protected $encoding = 'utf-8';
    
    public function setEncoding($value)
    {
        $this->encoding = $value; return $this;
    }
    
    public function getEncoding()
    {
        return $this->encoding;
    }
    
    
    /**
     * @ORM\Column(type="text")
     */
    protected $headers;
    
    public function setHeaders(Headers $headers)
    {
        $this->headers = $headers->toString();
        return $this;
    }
    
    public function getHeaders()
    {
        if ($this->headers)
            return Headers::fromString($this->headers);
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
	protected $body;
	
	public function setBody($mixed)
	{
	    $this->body = serialize($mixed); return $this;
	}
	
	public function getBody()
	{
	    if ($this->body)
	       return unserialize($this->body);
	}
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $bodyText;
	
	public function setBodyText($value)
	{
	    $this->bodyText = $value; return $this;
	}
	
	public function getBodyText()
	{
	    return $this->bodyText;
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

