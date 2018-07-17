<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository{

    public function getProEvent(){
        $today = date('Y-m-d');
        $query = $this->createQueryBuilder('e')
                        ->where('e.date >= :today')
                        ->leftJoin('e.owner', 'u')
                        ->addSelect('u')
                        ->leftJoin('u.company', 'c')
                        ->addSelect('c')
                        ->andWhere('c.lastPay >= :today')
                        ->setParameter('today', $today);

        return $query->getQuery()->getArrayResult();
    }
}
