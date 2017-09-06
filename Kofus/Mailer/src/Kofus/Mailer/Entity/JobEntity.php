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
    /*
    public function __construct()
    {
        $this->subscribers = new \Doctrine\Common\Collections\ArrayCollection();
    } */
    
    
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
     *     unique=true)}
     * )
     */
    protected $channels;
    
    public function setChannels(array $entities)
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
     * @ORM\Column(type="integer")
     */
    
    protected $status = 0;
    
    public static $STATUS = array(
        0 => 'init',
        1 => 'started',
        2 => 'completed',
        3 => 'aborted'
    );
    
    public function setStatus($value)
    {
        $this->status = $value; return $this;
    }
    
    public function getStatus()
    {
        return $this->status;
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

