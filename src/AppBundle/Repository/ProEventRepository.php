<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProEventRepository extends EntityRepository
{

    public function getNextProEvents(){
        $today = date('Y-m-d');
        $query = $this->createQueryBuilder('e')
                        ->addSelect('c')
                        ->where('e.date >= :today')
                        ->leftJoin('e.company', 'c')
                        ->andWhere('c.lastPay >= :today')
                        ->setParameter('today', $today);

        return $query->getQuery()->getArrayResult();
    }
}