<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Cache\QueryCacheProfile;

class TenderRepository extends EntityRepository
{
    public function getTenderList($filters)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('t','o', 'te')
            ->from('AppBundle\Entity\Tender', 't')
            ->leftJoin('t.organizer', 'o')
            ->leftJoin('o.team', 'te')
            ->where('t.datetime >= :today')
            ->andWhere('t.status = :status_open')
            ->setParameter('today', new \DateTime('now'))
            ->setParameter('status_open', Tender::STATUS_OPEN)
            ->orderBy('t.datetime', 'ASC');

        if ($filters) {
            $qb->andWhere('t.cityId = :city')
                ->setParameter('city', $filters['city']);

            if ($filters['district'] > 0) {
                $qb->andWhere('t.districtId = :district')
                    ->setParameter('district', $filters['district']);
            }
        }

        return $qb->getQuery();
    }

    public function getMyTenderList($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('t', 'COUNT(r.id) as r_count')
            ->from('AppBundle\Entity\Tender', 't')
            ->leftJoin('t.organizer', 'o')
            ->leftJoin('t.requests', 'r')
            ->where('t.datetime >= :today')
            ->andWhere('t.organizer = :user_id')
            ->andWhere('t.status = :open_status')
            ->setParameter('today', new \DateTime('now'))
            ->setParameter('user_id', $userId)
            ->setParameter('open_status', Tender::STATUS_OPEN)
            ->orderBy('t.datetime', 'ASC')
            ->groupBy('t.id');

        return $qb->getQuery();
    }

    public function getConfirmedRequests($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('t', 'r')
            ->from('AppBundle\Entity\Tender', 't')
            ->leftJoin('t.requests', 'r')
            ->where('t.organizer = :user_id')
            ->andWhere('r.status = :status_confirmed')
            ->setParameter('user_id', $userId)
            ->setParameter('status_confirmed', RequestGame::STATUS_APPROVED);

        return $qb->getQuery();
   }

    public function getMyTenderClosedList($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('t')
            ->from('AppBundle\Entity\Tender', 't')
            ->leftJoin('t.organizer', 'o')
            ->leftJoin('t.requests', 'r')
            ->andWhere('t.organizer = :user_id')
            ->andWhere('t.status = :closed_status')
            ->orWhere('r.team = :user_id AND r.status = :request_status_approved')
            ->setParameter('user_id', $userId)
            ->setParameter('closed_status', Tender::STATUS_CLOSED)
            ->setParameter('request_status_approved', RequestGame::STATUS_APPROVED)
            ->orderBy('t.datetime', 'ASC')
            ->groupBy('t.id');

        return $qb->getQuery();
    }
}