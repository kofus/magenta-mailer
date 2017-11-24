<?php

namespace Kofus\Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Node\NodeInterface;




/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_jobs")
 *
 */
class JobEntity implements NodeInterface
{
    public function __construct()
    {
        $this->channels = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @ORM\ManyToOne(targetEntity="Kofus\Mailer\Entity\NewsEntity")
     */
    protected $news;
    
    public function setNews(NewsEntity $entity)
    {
        $this->news = $entity; return $this;
    }
    
    public function getNews()
    {
        return $this->news;
    }
    
    /**
     * @ORM\ManyToMany(targetEntity="Kofus\Mailer\Entity\NewsChannelEntity")
     * @ORM\JoinTable(name="kofus_mailer_jobs_channels",
     *     joinColumns={@ORM\JoinColumn(name="job_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="channel_id", referencedColumnName="id",
     *     unique=false)}
     * )
     */
    protected $channels;
    
    public function setChannels($entities)
    {
    	$this->channels = $entities; return $this;
    }
    
    public function getChannels()
    {
    	return $this->channels;
    }
    
    /**
     * @ORM\ManyToMany(targetEntity="Kofus\Mailer\Entity\NewsSubscriberEntity")
     * @ORM\JoinTable(name="kofus_mailer_jobs_subscribers",
     *     joinColumns={@ORM\JoinColumn(name="job_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="subscriber_id", referencedColumnName="id",
     *     unique=true)}
     * )
     */
    protected $subscribers = array();
    
    public function setSubscribers(array $entities)
    {
        $this->subscribers = $entities; return $this;
    }
    
    public function getSubscribers()
    {
        return $this->subscribers;
    }
    
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestampScheduled;
    
    public function setTimestampScheduled(\DateTime $dt)
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
    protected $timestampCompleted;
    
    public function setTimestampCompleted(\DateTime $dt)
    {
        $this->timestampCompleted = $dt; return $this;
    }
    
    public function getTimestampCompleted()
    {
        return $this->timestampCompleted;
    }
    
    
    
    /**
     * @ORM\Column(type="integer")
     */
    
    protected $status = 0;
    
    public static $STATUS = array(
        0 => 'scheduled',
        1 => 'started',
        2 => 'completed',
        3 => 'aborted'
    );
    
    public function setStatus($value)
    {
        $this->status = $value; return $this;
    }
    
    public function getStatus($pretty=false)
    {
        if ($pretty)
            return self::$STATUS[$this->status];
        return $this->status;
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
     * @ORM\Column(type="json_array")
     */
    protected $params = array();
    
    public function setParams(array $values)
    {
        $this->params = $values; return $this;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function setParam($key, $value)
    {
        $this->params[$key] = $value; return $this;
    }
    
    public function getParam($key)
    {
        if (isset($this->params[$key]))
            return $this->params[$key];
    }
    
    
	public function getNodeType()
	{
		return 'MJ';
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

