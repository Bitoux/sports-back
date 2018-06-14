<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FriendRepository extends EntityRepository{
    
    public function checkUsers($users){
        $query = $this->createQueryBuilder('a')
                      ->select('a')
                      ->leftJoin('a.users', 'c')
                      ->addSelect('c');
 
        $query = $query->add('where', $query->expr()->in('c', ':c'))
                      ->setParameter('c', $users)
                      ->getQuery()
                      ->getResult();
          
        return $query;
    }
}