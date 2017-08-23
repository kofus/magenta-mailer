<?php

namespace Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kofus\System\Entity\EmailAddressEntity;
use Kofus\System\Node\NodeInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="kofus_mailer_mailings")
 */
class MailingEntity implements NodeInterface
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
     * @ORM\ManyToOne(targetEntity="Kofus\Mailer\Entity\NewsgroupEntity")
     */
    protected $newsgroup;
    
    public function setNewsgroup(NewsgroupEntity $entity)
    {
    	$this->newsgroup = $entity; return $this;
    }
    
    public function getNewsgroup()
    {
    	return $this->newsgroup;
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="Kofus\Mailer\Entity\TemplateEntity")
     */
    protected $template;
    
    public function setTemplate(TemplateEntity $entity)
    {
    	$this->template = $entity; return $this;
    }
    
    public function getTemplate()
    {
    	return $this->template;
    }
    
    /**
     * @ORM\Column(length=15)
     */
    protected $status = 'draft';
    
    public static $STATUS = array(
    	'draft' => 'Entwurf',
        'scheduled' => 'für Versand vorgemerkt',
        'sending' => 'wird gerade versendet',
        'completed' => 'Versand erfolgreich abgeschlossen',
        'cancelled' => 'abgebrochen'
    );
    
    public function setStatus($value)
    {
        if (! isset(self::$STATUS[$value]))
            throw new \Exception('Unknown status: ' . $value);
    	$this->status = $value; return $this;
    }
    
    public function getStatus($pretty=false)
    {
        if ($pretty)
            return self::$STATUS[$this->status];
    	return $this->status;
    }
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $timestampScheduled;
    
    public function setTimestampScheduled(\DateTime $value=null)
    {
        $this->timestampScheduled = $value; return $this;
    }
    
    public function getTimestampScheduled()
    {
        return $this->timestampScheduled;
    }
    
    public function getNodeId()
    {
        return $this->getNodeType() . $this->getId();
    }
	
	public function getNodeType()
	{
		return 'MAILING';
	}
	
	public function __toString()
	{
		return $this->getNodeId();	
	}
}

