<?php
/**
 * Created by PhpStorm.
 * User: Usamo
 * Date: 29.03.2018
 * Time: 20:34
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Cache\QueryCacheProfile;

class MessageRepository extends EntityRepository
{
    public function getUserMessages($teamId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(m.id) as count_msg','m')
            ->from('AppBundle\Entity\Message', 'm')
            ->where('m.sender = :team_id')
            ->orWhere('m.receiver = :team_id')
            ->setParameter('team_id', $teamId)
            ->setParameter('team_id', $teamId)
            ->groupBy('m.treadId')
            ->orderBy('m.id', 'DESC');

        return $qb->getQuery();
    }

    public function getTreadMessages($treadId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('m')
            ->from('AppBundle\Entity\Message', 'm')
            ->where('m.treadId = :tread_id')
            ->setParameter('tread_id', $treadId)
            ->orderBy('m.createdAt', 'ASC');

        return $qb->getQuery();
    }

}