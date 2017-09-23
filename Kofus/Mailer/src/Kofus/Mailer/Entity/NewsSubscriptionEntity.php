<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node\NodeInterface;




/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_subscriptions", uniqueConstraints={@ORM\UniqueConstraint(name="unique", columns={"subscriberId", "channel_id"})})
 *
 */
class NewsSubscriptionEntity implements NodeInterface
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
    protected $subscriberId;
    
    public function setSubscriberId($value)
    {
        $this->subscriberId = $value; return $this;
    }
    
    public function getSubscriberId()
    {
        return $this->subscriberId;
    }
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Kofus\Mailer\Entity\NewsChannelEntity")
     */
    protected $channel;
    
    public function setChannel(NewsChannelEntity $entity)
    {
    	$this->channel = $entity; return $this;
    }
    
    public function getChannel()
    {
    	return $this->channel;
    }
    
    /**
     * @ORM\Column(length=32, nullable=true)
     */
    protected $activationToken;
    
    public function setActivationToken($value)
    {
        $this->activationToken = $value; return $this;
    }
    
    public function getActivationToken()
    {
        return $this->activationToken;
    }
    
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $timestampActivationSubscriber;
    
    public function setTimestampActivationSubscriber(\DateTime $timestamp=null)
    {
        $this->timestampActivationSubscriber = $timestamp; return $this;
    }
    
    public function getTimestampActivationSubscriber()
    {
        return $this->timestampActivationSubscriber;
    }
    
    public static $STATUS = array(
        'pending' => 'Bestätigung durch Abonnent erforderlich',
        'active' => 'abonniert'
    );
    
    public function getStatus($pretty=false)
    {
        $status = 'active';
        if (! $this->getTimestampActivationSubscriber())
            $status = 'pending';
        
        if ($pretty)
            return self::$STATUS[$status];
        return $status;
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
		return $this->getNodeId();	
	}
}

