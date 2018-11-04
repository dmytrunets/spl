<?php
/**
 * Created by PhpStorm.
 * User: Usamo
 * Date: 29.03.2018
 * Time: 13:10
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * AppBundle\Entity\Request
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MessageRepository")
 */
class Message
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(name="tread_id", type="text")
     */
    protected $treadId;
    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\Tender", inversedBy="messages")
     * @JoinColumn(name="tender_id", referencedColumnName="id")
     */
    private $tender;
    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\Team")
     * @JoinColumn(name="sender_team_id", referencedColumnName="id")
     */
    private $sender;
    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\Team")
     * @JoinColumn(name="receiver_team_id", referencedColumnName="id")
     */
    private $receiver;
    /**
     * @ORM\Column(name="content", type="text")
     */
    protected $content;
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTreadId()
    {
        return $this->treadId;
    }

    /**
     * @param mixed $treadId
     */
    public function setTreadId($treadId)
    {
        $this->treadId = $treadId;
    }


    /**
     * @return Tender
     */
    public function getTender()
    {
        return $this->tender;
    }

    /**
     * @param Tender $tender
     */
    public function setTender($tender)
    {
        $this->tender = $tender;
    }

    /**
     * @return Team
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param Team $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return Team
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param Team $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}