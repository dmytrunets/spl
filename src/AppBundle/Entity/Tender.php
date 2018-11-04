<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * AppBundle\Entity\Tender
 *
 * @ORM\Table(name="tender")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TenderRepository")
 */
class Tender
{
    const STATUS_OPEN = 0;
    const STATUS_CLOSED = 1;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(name="city_id", type="integer")
     */
    protected $cityId;
    /**
     * @ORM\Column(name="district_id", type="integer")
     */
    protected $districtId;
    /**
     * @ORM\Column(name="geo_point", type="text")
     */
    protected $geoPoint;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datetime;
    /**
     * @ORM\Column(name="max_team", type="integer")
     */
    protected $maxTeam = 2;
    /**
     * @ORM\Column(name="max_player", type="integer")
     */
    protected $maxPlayer = 5;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RequestGame", mappedBy="tender")
     * @JoinColumn(name="tender_id", referencedColumnName="id")
     */
    private $requests = array();
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="tenders")
     */
    protected $organizer;
    /**
     * @ORM\Column(name="status", type="integer")
     */
    protected $status = Tender::STATUS_OPEN;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="tender")
     */
    private $messages;

    public function __construct()
    {
        $this->datetime = new \DateTime();
    }

    public function setCityId($value)
    {
        $this->cityId = $value;
    }

    public function setDistrictId($value)
    {
        $this->districtId = $value;
    }

    public function setGeoPoint($value)
    {
        $this->geoPoint = $value;
    }

    public function setDateTime($value)
    {
        $this->datetime = $value;
    }

    public function setOrganizer($value)
    {
        $this->organizer = $value;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * @return mixed
     */
    public function getDistrictId()
    {
        return $this->districtId;
    }

    public function getDistrictName()
    {
        $districts = array(
            1 => 'Святошинский',
            2 => 'Соломенский',
            3 => 'Деснянский',
            4 => 'Дарницкий',
            5 => 'Подольский',
            6 => 'Оболонский',
            7 => 'Днепровский',
            8 => 'Шевченковский',
            9 => 'Печерский',
            10 => 'Голосеевский'
        );

        return $districts[$this->getDistrictId()];
    }

    /**
     * @return mixed
     */
    public function getGeoPoint($index = '')
    {
        $geo = explode(',', $this->geoPoint);
        if ($index == 'lat') {
            return $geo[0];
        } elseif ($index == 'lng') {
            return $geo[1];
        }

        return $this->geoPoint;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @return mixed
     */
    public function getMaxTeam()
    {
        return $this->maxTeam;
    }

    /**
     * @return mixed
     */
    public function getMaxPlayer()
    {
        return $this->maxPlayer;
    }

    /**
     * @return mixed
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @return mixed
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param mixed $requests
     */
    public function setRequests($requests)
    {
        $this->requests = $requests;
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
    public function getMessages()
    {
        return $this->messages;
    }

    public function getTitle()
    {
        return 'Встреча 5х5';
    }
}