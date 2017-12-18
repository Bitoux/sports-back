<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Event;
use AppBundle\Entity\Filter;
use AppBundle\Entity\Grade;
use AppBundle\Entity\Map;
use AppBundle\Entity\Spot;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //Initialisation de la base
        $basketFilter = new Filter();
        $basketFilter->setDesignation('Basket-ball');
        $footFilter = new Filter();
        $footFilter->setDesignation('Football');
        $rugbyFilter = new Filter();
        $rugbyFilter->setDesignation('Rugby');
        $shopFilter = new Filter();
        $shopFilter->setDesignation('Shops');
        $fakeFilters = array($basketFilter, $footFilter);

        $fakeGrade = new Grade();
        $fakeGrade->setGrade(1);
        $fakeGrade2 = new Grade();
        $fakeGrade2->setGrade(2);
        $fakeGrade3 = new Grade();
        $fakeGrade3->setGrade(3);
        $fakeGrade4 = new Grade();
        $fakeGrade4->setGrade(4);
        $fakeGrade5 = new Grade();
        $fakeGrade5->setGrade(5);
        $fakeGrades = array($fakeGrade5, $fakeGrade3, $fakeGrade4);

        $fakeUser = new User();
        $fakeUser->setFirstName('Jean');
        $fakeUser->setLastName('Moulin');
        $fakeUser->setUserName('JMoulin');
        $fakeUser->setPassword('$2y$13$JOvZdRIR9vAjfcRCD.ReLOnqWE9EG9kVcvQUesN2BTRZVg.hikIba');
        $fakeUser->setEmail('jmoulin@fake.com');
        $fakeUser->setAdress('9 avenue de la République');
        $fakeUser->setCity('Paris');
        $fakeUser->setCountry('France');
       /* $fakeUser->setBirthday('15/04/1993');*/
        $fakeUser->setRoles(array('ROLE_READER'));
        $fakeUser->setEnabled(true);

        $fakeMap = new Map();
        $fakeMap->setName("map 1");
        $fakeMaps = array($fakeMap);

        $fakeSpot = new Spot();
        $fakeSpot->setLongitude('48° 52\' 14.84\'\', 2° 19\' 0.8\'\'');
        $fakeSpot->setLatitude('48.87079, 2.31689');
        $fakeSpot->setName('spot 1');
        $fakeSpots = array($fakeSpot);

        $fakeEvent = new Event();
        $fakeEvent->setName('Match 1');
        /*$fakeEvent->setDate('15-04-2018');*/
        $fakeEvent->setSubject('Premier match de la saison !');
        $fakeEvent->setDescription('Ramenez tous vos short, ca va être une aprem....');
        $fakeEvent->setNbUser(15);
        $fakeEvents = array($fakeEvent);

        $manager->persist($basketFilter);
        $manager->persist($footFilter);
        $manager->persist($rugbyFilter);
        $manager->persist($shopFilter);
        $manager->persist($fakeGrade);
        $manager->persist($fakeGrade2);
        $manager->persist($fakeGrade3);
        $manager->persist($fakeGrade4);
        $manager->persist($fakeGrade5);
       /* $manager->persist($fakeUser);
        $manager->persist($fakeEvent);
        $manager->persist($fakeSpot);
        $manager->persist($fakeMap);*/

        //Création des relations
       /* $fakeUser->setMap($fakeMap);
        $fakeUer->setFilters($fakeFilters);
        $fakeUser->setGrades($fakeGrades);

        $fakeMap->setSpots($fakeSpots);

        $fakeSpot->setFilters($fakeFilters);
        $fakeSpot->setEvents($fakeEvents);
        $fakeSpot->setGrades($fakeGrades);

        $fakeEvent->setOwner($fakeUser->getId());*/

        $manager->flush();
    }
}