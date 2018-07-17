<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository{

    public function getProEvent(){
        $today = date('Y-m-d');
        $query = $this->createQueryBuilder('e')
                        ->where('e.date >= :today')
                        ->leftJoin('e.spot', 's')
                        ->addSelect('s')
                        ->leftJoin('e.filters', 'f')
                        ->addSelect('f')
                        ->leftJoin('e.owner', 'u')
                        ->addSelect('u')
                        ->leftJoin('e.users', 'us')
                        ->addSelect('us')
                        ->leftJoin('u.company', 'c')
                        ->addSelect('c')
                        ->andWhere('c.lastPay >= :today')
                        ->setParameter('today', $today);

        return $query->getQuery()->getArrayResult();
    }
}
