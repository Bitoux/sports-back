<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $fakeUser = new User();
        $fakeUser->setFirstName('Timothé');
        $fakeUser->setLastName('Molavy');
        $fakeUser->setUserName('Timo');
        $fakeUser->setPassword('1234');
        $fakeUser->setEmail('timo@fake.com');
        $fakeUser->setAdresse('9 avenue de la République');
        $fakeUser->setCity('Paris');
        $fakeUser->setCountry('France');
        $fakeUser->setBirthday('15/04/1993');

        $fakeUser2 = new User();
        $fakeUser2->setFirstName('Yann');
        $fakeUser2->setLastName('Miloux');
        $fakeUser2->setUserName('Bitoux');
        $fakeUser2->setPassword('1234');
        $fakeUser2->setEmail('yann@fake.com');
        $fakeUser2->setAdresse('13 avenue de la République');
        $fakeUser2->setCity('Paris');
        $fakeUser2->setCountry('France');
        $fakeUser2->setBirthday('17/11/1992');

        $manager->persist($fakeUser);
        $manager->persist($fakeUser2);
        $manager->flush();
    }
}