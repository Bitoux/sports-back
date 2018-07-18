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
        $tennisFilter = new Filter();
        $tennisFilter->setDesignation('Tennis');
        $boxeFilter = new Filter();
        $boxeFilter->setDesignation('Boxe');
        $volleyFilter = new Filter();
        $volleyFilter->setDesignation('Volley-ball');
        $runningFilter = new Filter();
        $runningFilter->setDesignation('Running');
        $workoutFilter = new Filter();
        $workoutFilter->setDesignation('Workout');
        $racingFilter = new Filter();
        $racingFilter->setDesignation('Racing');
        $shopFilter = new Filter();
        $shopFilter->setDesignation('Shops');
        $fakeFilters = array($basketFilter, $footFilter);

        $fakeUser = $managerFos->createUser();
        $fakeUser->setFirstName('Jean');
        $fakeUser->setLastName('Moulin');
        $fakeUser->setUserName('JMoulin_37');
        $fakeUser->setPassword('$2y$13$JOvZdRIR9vAjfcRCD.ReLOnqWE9EG9kVcvQUesN2BTRZVg.hikIba');
        $fakeUser->setEmail('yannmiloux@gmail.com');
        $fakeUser->setAdress('9 avenue de la République');
        $fakeUser->setCity('Paris');
        $fakeUser->setCountry('France');
        $fakeUser->setRoles(array('ROLE_READER'));
        $fakeUser->setEnabled(true);

        $fakeMap = new Map();
        $fakeMap->setName("map 1");

        $fakeSpot = new Spot();
        $fakeSpot->setLongitude('2.3705932');
        $fakeSpot->setLatitude('48.8300412');
        $fakeSpot->setAddress('9 Rue Dunois, 75013 Paris, France');
        $fakeSpot->setName('Exemple');
        $fakeSpots = array($fakeSpot);


        $manager->persist($basketFilter);
        $manager->persist($footFilter);
        $manager->persist($tennisFilter);
        $manager->persist($boxeFilter);
        $manager->persist($runningFilter);
        $manager->persist($volleyFilter);
        $manager->persist($workoutFilter);
        $manager->persist($racingFilter);
        $manager->persist($shopFilter);
        $managerFos->updateUser($fakeUser);
        $manager->persist($fakeSpot);
        $manager->persist($fakeMap);

        //Création des relations
        $fakeUser->setMap($fakeMap);
        $fakeUser->setFilters($fakeFilters);

        $fakeMap->setSpots($fakeSpots);
        $fakeSpot->setFilters($fakeFilters);

        $manager->flush();
    }
}