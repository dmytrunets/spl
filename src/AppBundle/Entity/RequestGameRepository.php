<?php
/**
 * Created by PhpStorm.
 * User: Usamo
 * Date: 29.03.2018
 * Time: 14:52
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Cache\QueryCacheProfile;

class RequestGameRepository extends EntityRepository
{
    public function getMyRequestedTenderList($teamId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('r', 't', 'o')
            ->from('AppBundle\Entity\RequestGame', 'r')
            ->leftJoin('r.tender', 't')
            ->leftJoin('t.organizer', 'o')
            ->where('r.team = :team_id')
//            ->andWhere('t.datetime >= :today')
            ->setParameter('team_id', $teamId)
//            ->setParameter('today', new \DateTime('now'))
            ->orderBy('t.datetime', 'ASC');

        return $qb->getQuery();
    }

    public function getRequestsByTender($tenderId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('r', 't', 'o')
            ->from('AppBundle\Entity\RequestGame', 'r')
            ->leftJoin('r.tender', 't')
            ->leftJoin('t.organizer', 'o')
            ->where('t.id = :tender_id')
            ->setParameter('tender_id', $tenderId)
            ->orderBy('t.datetime', 'ASC');

        return $qb->getQuery();
    }
}