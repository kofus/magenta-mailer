<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node\NodeInterface;




/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_subscriptions", uniqueConstraints={@ORM\UniqueConstraint(name="unique", columns={"subscriber_id", "channel_id"})})
 *
 */
class SubscriptionEntity implements NodeInterface
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
     * @ORM\ManyToOne(targetEntity="Kofus\Mailer\Entity\SubscriberEntity")
     */
    protected $subscriber;
    
    public function setSubscriber(SubscriberEntity $entity)
    {
        $this->subscriber = $entity; return $this;
    }
    
    public function getSubscriber()
    {
        return $this->subscriber;
    }
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Kofus\Mailer\Entity\ChannelEntity")
     */
    protected $channel;
    
    public function setChannel(ChannelEntity $entity)
    {
    	$this->channel = $entity; return $this;
    }
    
    public function getChannel()
    {
    	return $this->channel;
    }
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $timestampActivation;
    
    public function setTimestampActivation(\DateTime $timestamp=null)
    {
        $this->timestampActivation = $timestamp; return $this;
    }
    
    public function getTimestampActivation()
    {
        return $this->timestampActivation;
    }
    
	public function getNodeType()
	{
		return 'SCP';
	}
	
	public function getNodeId()
	{
	    return $this->getNodeType() . $this->getId();
	}
	
	public function __toString()
	{
		$s = 'Abonnement / ';
		if ($this->getSubscriber()) {
		    $s .= $this->getSubscriber()->getEmailAddress() . ' ';
		    if ($this->getSubscriber()->getName())
		        $s .= ' (' . $this->getSubscriber()->getName() . ') ';
		    $s .= '/ ';
		}
		
		if ($this->getChannel()) {
		    $s .= $this->getChannel()->getTitle();
		}
		return $s . ' (' . $this->getNodeId() . ')';
	}
}

