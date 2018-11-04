<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping as ORM;

/**
 * AppBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User extends BaseUser
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
     * One User has One Team
     * @OneToOne(targetEntity="AppBundle\Entity\Team", mappedBy="user")
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tender", mappedBy="organizer")
     */
    private $tenders;

    /**
     * @return Team[]
     */
    public function getTeam()
    {
        return $this->team;
    }

}
