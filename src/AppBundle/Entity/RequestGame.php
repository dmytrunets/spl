<?php
/**
 * Created by PhpStorm.
 * User: Usamo
 * Date: 29.03.2018
 * Time: 13:09
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * AppBundle\Entity\Request
 *
 * @ORM\Table(name="request")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RequestGameRepository")
 */
class RequestGame
{
    const STATUS_NEW = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\Tender", inversedBy="requests")
     * @JoinColumn(name="tender_id", referencedColumnName="id")
     */
    private $tender;
    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\Team", inversedBy="requests")
     * @JoinColumn(name="team_id", referencedColumnName="id")
     */
    private $team;
    /**
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;
    /**
     * @ORM\Column(name="create_at", type="datetime")
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = self::STATUS_NEW;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Tender[]
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
     * @return Team[]
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getStatusTitle()
    {
        $status = array(
            self::STATUS_NEW => 'Не подтверждена',
            self::STATUS_APPROVED => 'Подтверждена',
            self::STATUS_REJECTED => 'Отклонена'
        );

        return $status[$this->status];
    }
}