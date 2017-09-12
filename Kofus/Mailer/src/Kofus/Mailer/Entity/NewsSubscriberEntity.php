<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node\NodeInterface;
use Kofus\Mailer\Entity\NewsgroupEntity;



/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_subscribers", uniqueConstraints={@ORM\UniqueConstraint(name="emailAddress", columns={"emailAddress"})})
 */
class NewsSubscriberEntity implements NodeInterface
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
    protected $params=array();
    
    public function setParam($key, $value)
    {
        $this->params[$key] = $value; return $this;
    }
    
    public function getParam($key)
    {
        if (isset($this->params[$key]))
            return $this->params[$key];
    }
    
    public function unsetParam($key)
    {
        if (isset($this->params[$key]))
            unset($this->params[$key]);
        return $this;
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

