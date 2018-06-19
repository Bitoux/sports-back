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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;


class LoadPostData implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $managerFos = $this->container->get('fos_user.user_manager');

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

        $fakeUser = $managerFos->createUser();
        $fakeUser->setFirstName('Jean');
        $fakeUser->setLastName('Moulin');
        $fakeUser->setUserName('JMoulin');
        $fakeUser->setPassword('$2y$13$JOvZdRIR9vAjfcRCD.ReLOnqWE9EG9kVcvQUesN2BTRZVg.hikIba');
        $fakeUser->setEmail('yannmiloux@gmail.com');
        $fakeUser->setAdress('9 avenue de la République');
        $fakeUser->setCity('Paris');
        $fakeUser->setCountry('France');
        $fakeUser->setRoles(array('ROLE_READER'));
        $fakeUser->setEnabled(true);

        $fakeMap = new Map();
        $fakeMap->setName("map 1");
        $fakeMaps = array($fakeMap);

        $fakeSpot = new Spot();
        $fakeSpot->setLongitude('2.3705932');
        $fakeSpot->setLatitude('48.8300412');
        $fakeSpot->setAddress('9 Rue Dunois, 75013 Paris, France');
        $fakeSpot->setName('Exemple');
        $fakeSpots = array($fakeSpot);

        $fakeEvent = new Event();
        $fakeEvent->setName('Aprem 4vs4');
        $fakeEvent->setDate('2018-01-25');
        $fakeEvent->setSubject('4vs4 de Basket !');
        $fakeEvent->setDescription('Bonjour ! j\'aimerai faire un basket Jeudi 25 Janvier et je cherche du monde ! ');
        $fakeEvent->setNbUser(8);
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
        $managerFos->updateUser($fakeUser);
        $manager->persist($fakeEvent);
        $manager->persist($fakeSpot);
        $manager->persist($fakeMap);

        //Création des relations
        $fakeUser->setMap($fakeMap);
        $fakeUser->setFilters($fakeFilters);
        $fakeUser->setGrades($fakeGrades);

        $fakeMap->setSpots($fakeSpots);

        $fakeSpot->setFilters($fakeFilters);
        $fakeSpot->setGrades($fakeGrades);

        $fakeEvent->setOwner($fakeUser->getId());
        $fakeEvent->setSpot($fakeSpot);

        $manager->flush();
    }
}