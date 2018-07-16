<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SpotRepository extends EntityRepository{

    public function getShopsSpot(){
        $today = date('Y-m-d');
        $query = $this->createQueryBuilder('s')
                        ->addSelect('c')
                        ->leftJoin('s.company', 'c')
                        ->where('c.lastPay >= :today')
                        ->setParameter('today', $today);

        return $query->getQuery()->getArrayResult();
    }
    

}