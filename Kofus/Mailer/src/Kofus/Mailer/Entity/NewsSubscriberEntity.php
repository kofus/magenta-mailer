<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node\NodeInterface;
use Kofus\Mailer\NewsSubscriberInterface;



/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_subscribers", uniqueConstraints={@ORM\UniqueConstraint(name="emailAddress", columns={"emailAddress"}), @ORM\UniqueConstraint(name="token", columns={"token"})})
 */
class NewsSubscriberEntity implements NodeInterface, NewsSubscriberInterface
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
    protected $emailAddress;
    
    public function setEmailAddress($value)
    {
    	$this->emailAddress = $value; return $this;
    }
    
    public function getEmailAddress()
    {
    	return $this->emailAddress;
    }
    
    /**
     * @ORM\Column(type="json_array")
     */
    protected $mailerParams=array();
    
    public function setMailerParam($key, $value)
    {
        $this->mailerParams[$key] = $value; return $this;
    }
    
    public function getMailerParam($key)
    {
        if (isset($this->mailerParams[$key]))
            return $this->mailerParams[$key];
    }
    
    public function unsetMailerParam($key)
    {
        if (isset($this->mailerParams[$key]))
            unset($this->mailerParams[$key]);
        return $this;
    }
    
    public function getMailerParams()
    {
        return $this->mailerParams;
    }
    
    /**
     * @ORM\Column()
     */
    protected $token;
    
    public function setToken($value)
    {
        $this->token = $value; return $this;
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    
    
	public function getNodeType()
	{
		return 'SCB';
	}
	
	public function getNodeId()
	{
	    return $this->getNodeType() . $this->getId();
	}
	
	public function __toString()
	{
		return $this->getEmailAddress() . ' (' . $this->getNodeId() . ')';	
	}
}

